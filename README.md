# iRentBack

iRentnack é uma API em PHP para o MVP do iRent.


## Funcionalidades disponíveis já no MVP

- Cadastro Usuários
- Autenticação de Usuários
- Captcha meia boca mas que funciona rsrsrs
- Pesquisa dos imóveis com filtragem
- Persistência no MySQL (RDBMS)

## Planejado se for continuar o projeto:

- Reservas (planejado para versões futuras)
- Avaliações (planejado para versões futuras)

## Tecnologias utilizadas

### Front-end:

* Link para o iRent APP: https://github.com/viniciuscgp/irent
---

### Back-end:

- PHP 8
- MySQL
- Framework Flight (simples)
- Hopedagem compartilhada da Hostgator



## Pré-requisitos pra baixar as libs necessárias

Certifique-se de ter o Git e o Composer instalados em sua máquina. Eles são necessários para clonar o projeto e instalar as dependências.

Você pode verificar se já tem o Git e o Composer instalados com os seguintes comandos:

```
git --version
composer --version
```

Se você não os tiver instalados, você pode obter o Git [aqui](https://git-scm.com/downloads) e o Composer [aqui](https://getcomposer.org/download/).

## Clonando o Projeto

Para clonar o projeto, navegue até o diretório onde deseja clonar o projeto em sua máquina e execute o seguinte comando:

```
git clone https://github.com/viniciuscgp/irentback
```

## Instalando as Dependências

Depois de clonar o projeto, você precisa instalar as dependências do projeto. Vá para o diretório do projeto e execute o seguinte comando:

```
composer install
```

Este comando instala todas as dependências necessárias para o projeto.

* Agora você precisar mover o projeto para um servidor que suporte PHP + MySQL. 
* O arquivo .env é onde você pode configurar o seu ambiente.



## Licença

[MIT](https://choosealicense.com/licenses/mit/)

