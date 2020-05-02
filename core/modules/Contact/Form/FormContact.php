<?php

namespace SoosyzeCore\Contact\Form;

use Soosyze\Components\Form\FormBuilder;

class FormContact extends FormBuilder
{
    protected $values = [
        'name'    => '',
        'email'   => '',
        'object'  => '',
        'message' => '',
    ];

    public function setValues($values)
    {
        $this->values = array_merge($this->values, $values);
    }
    
    public function makeFields()
    {
        return $this->name()
                ->email()
                ->object()
                ->message()
                ->copy()
                ->token('token_contact')
                ->submit('submit', t('Send the message'), [ 'class' => 'btn btn-success' ]);
    }

    protected function name()
    {
        return $this->group('name-group', 'div', function ($form) {
            $form->label('name-label', t('Name'))
                    ->text('name', [
                        'class'    => 'form-control',
                        'required' => 1,
                        'value'    => $this->values[ 'name' ]
                ]);
        }, [ 'class' => 'form-group' ]);
    }

    protected function email()
    {
        return $this->group('email-group', 'div', function ($form) {
            $form->label('email-label', t('E-mail'))
                    ->email('email', [
                        'class'    => 'form-control',
                        'required' => 1,
                        'value'    => $this->values[ 'email' ]
                ]);
        }, [ 'class' => 'form-group' ]);
    }

    protected function object()
    {
        return $this->group('object-group', 'div', function ($form) {
            $form->label('object-label', t('Object'))
                    ->text('object', [
                        'class'    => 'form-control',
                        'required' => 1,
                        'value'    => $this->values[ 'object' ]
                ]);
        }, [ 'class' => 'form-group' ]);
    }

    protected function message()
    {
        return $this->group('message-group', 'div', function ($form) {
            $form->label('message-label', t('Message'))
                    ->textarea('message', $this->values[ 'message' ], [
                        'class'    => 'form-control',
                        'required' => 1,
                        'rows'     => 8
                ]);
        }, [ 'class' => 'form-group' ]);
    }

    protected function copy()
    {
        return $this->group('copy-group', 'div', function ($form) {
            $form->checkbox('copy')
                    ->label('copy-label', '<i class="ui" aria-hidden="true"></i> ' . t('Send me a copy of the mail'), [
                        'for' => 'copy'
                    ]);
        }, [ 'class' => 'form-group' ]);
    }
}
