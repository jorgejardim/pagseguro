#PagSeguro Component CakePHP 3.x

Adaptação do component para CakePHP 3.0 de André Cavallari para realização de pagamentos via PagSeguro.

## Instalação

Via composer:
```
require: "jorge/pagseguro": "dev-master"
```

## Configuração

```
$this->loadComponent('Pagseguro.Pagseguro');```
```

## Exemplo

```
$this->Pagseguro->redirectURL('/obrigado');
$this->Pagseguro->notificationURL('/notificacao');
$this->Pagseguro->setReference('REF0123');
$this->Pagseguro->add('0001', 'Notebook', 1, 40.00);
$this->Pagseguro->add('0002', 'Boné', 3, 30.00);
$this->Pagseguro->setSender(
    'João Comprador',
    'email@comprador.com.br',
    '11',
    '56273440'
    'CPF',
    '156.009.442-76'
);
$this->Pagseguro->setShippingAddress(
    '01452002',
    'Av. Brig. Faria Lima',
    '1384',
    'apto. 114',
    'Jardim Paulistano',
    'São Paulo',
    'SP',
    'BRA'
);
$url = $this->Pagseguro->register('email@pagseguro.com.br', 'tokenpagseguro');
return $this->redirect($url);
