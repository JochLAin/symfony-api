<?php 

namespace JochLAin\API\Exception;

use Symfony\Component\Form\Form;

use JochLAin\API\Form\Encoder;

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