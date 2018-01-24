<?php 

namespace Jochlain\API\Exception;

use Symfony\Component\Form\Form;

use Jochlain\API\Form\Encoder;

/**
 * @author Jocelyn Faihy <jfaihy@gmail.com>
 */
class FormException extends \Exception
{
    protected $form;

    public function __construct(Form $form, string $message = null, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->form = $form;
    }

    public function getForm() { return $this->form; }
    public function getEncoded() { return Encoder::encode($this->form); }
}