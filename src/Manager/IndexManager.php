<?php

namespace Jochlain\API\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

use Jochlain\Database\Manager\Query\CountManager;
use Jochlain\Database\Manager\Query\FetchManager;
use Jochlain\API\Mapping\Catalog;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class IndexManager
{
    protected $cm;
    protected $em;
    protected $request;
    protected $stack;

    public function __construct(EntityManagerInterface $em, RequestStack $stack, CatalogManager $cm) {
        $this->cm = $cm;
        $this->em = $em;
        $this->request = $stack->getMasterRequest();
        $this->stack = $stack;
    }

    public function index(string $classname, array $properties = null, array $constraints = [], array $sorts = []) {
        return IndexManager::indexes($this->request, $this->em, $this->cm, $classname, $properties, $constraints, $sorts);
    }

	public static function indexes(Request $request, EntityManagerInterface $em, CatalogManager $cm, string $classname, array $properties = null, array $constraints = [], array $sorts = [])
	{
        $catalog = $cm->build($classname, $properties, $constraints, $sorts);

        $limit = (int) $request->get('limit', 20);
        $offset = (int) $request->get('offset', 0);
        $columns = $catalog->getColumns();
        $criterias = $catalog->getCriterias();
        $sorts = $catalog->getSorts();

        $count = CountManager::counts($em, $classname, $criterias);
        $items = FetchManager::fetches($em, $classname, $properties, $criterias, $offset, $limit, $sorts);

        return [
            'columns'   => $columns,
            'items'     => $items,

            'count'     => $count,
            'offset'    => $offset, 
            'limit'     => $limit, 
            'pages'     => (int)ceil((float)$count / (float)$limit),
            'page'      => (int)floor((float)$offset / (float)$limit) +1,
        ];
	}
}