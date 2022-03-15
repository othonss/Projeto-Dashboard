<?php
    /*
        2º Tarefa (Incluir back-end do projeto com PHP)
            - a) Criar classe responsável pela funções do projeto 
            - b) Criar classe responsável pela conexão ao banco de dados (com try catch)
            - c) Criar classe que fará a manipulação do projeto
            - d) Realizar as instâncias das classes criadas anteriormente
            - e) Criar as funções para exibir números e total de vendas 
    */

    // a)
    class Dashboard{
        public $dataInicio;
        public $dataFim;
        public $numeroVendas;
        public $totalVendas;
        public $totalDepesas;
        public $clientesAtivos;
        public $ativo = true;
        public $clientesInativos;
        public $inativo = false;
        public $criticas = 1;
        public $sugestoes = 2;
        public $elogios = 3;

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
            return $this;
        }
    }

    // b)
    class Conexao {
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar(){
            try {
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );
                //Para o banco obedecer a lista de caracteres disponíveis do front-end
                $conexao->exec('set charset utf8');
                return $conexao;

            } catch (PDOException $e){
                echo '<p>' . $e->getMessage() . '</p>';
            }
            
        }
    }

    // c)
    class Bd {
        private $conexao;
        private $dashboard;
        public function __construct(Conexao $conexao, Dashboard $dashboard){
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        // e)
        public function getNumeroVendas(){
            $query = '
                select
                    count(*) as numero_vendas
                from
                    tb_vendas
                where
                    data_venda between :dataInicio and :dataFim
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim',  $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        public function getTotalVendas(){
            $query = '
                select
                    SUM(total) as total_vendas
                from
                    tb_vendas
                where
                    data_venda between :dataInicio and :dataFim
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim',  $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }

        public function getTotalDespesas(){
            $query = '
                select
                    SUM(total) as total_despesas
                from
                    tb_despesas
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
        }


        public function getClientesAtivos(){
            $query = '
                select 
                    count(*) as clientes_ativos 
                from 
                    tb_clientes 
                where 
                cliente_ativo = :ativo;
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':ativo', $this->dashboard->__get('ativo'));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;
        }

        public function getClientesInativos(){
            $query = '
                select 
                    count(*) as clientes_inativos 
                from 
                    tb_clientes 
                where 
                    cliente_ativo = :inativo;
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':inativo', $this->dashboard->__get('inativo'));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos;
        }

        public function getCriticas(){
            $query = '
                select 
                    count(*) as total_criticas
                from
                    tb_contatos
                where
                    tipo_contato = :criticas;
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':criticas', $this->dashboard->__get('criticas'));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->total_criticas;
        }

        public function getElogios(){
            $query = '
                select 
                    count(*) as total_elogios
                from
                    tb_contatos
                where
                    tipo_contato = :elogios;
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':elogios', $this->dashboard->__get('elogios'));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
        }

        public function getSugestoes(){
            $query = '
                select 
                    count(*) as total_sugestoes
                from
                    tb_contatos
                where
                    tipo_contato = :sugestoes;
            ';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':sugestoes', $this->dashboard->__get('sugestoes'));
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
        }
    }

    // d)
    $dashboard = new Dashboard();
    
    $conexao = new Conexao();
    
    //c)
    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];
    // Função do PHP que permite saber quando dias o mês tem
    $diasDoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    $dashboard->__set('dataInicio', $ano.'-'.$mes.'-01');
    $dashboard->__set('dataFim', $ano.'-'.$mes.'-'.$diasDoMes);

    $bd = new Bd($conexao, $dashboard);
    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());
    $dashboard->__set('clientesAtivos', $bd->getClientesAtivos());
    $dashboard->__set('clientesInativos', $bd->getClientesInativos());
    $dashboard->__set('criticas', $bd->getCriticas());
    $dashboard->__set('elogios', $bd->getElogios());
    $dashboard->__set('sugestoes', $bd->getSugestoes());
    $dashboard->__set('totalDepesas', $bd->getTotalDespesas());
     // d)
    echo json_encode($dashboard);
?>