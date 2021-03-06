<?php
/**
 * Class Base
 *
 * @package     cakegrid.Config.Migrations
 */
use Migrations\AbstractMigration;
use Cake\Auth\DefaultPasswordHasher;
/**
 * Mantém o banco de dados inicial.
 *
 * $ bin/cake migrations create Base // este comando irá criar as tabelas, não rode aqui.
 * $ bin/cake migrations status
 * $ bin/cake migrations migrate
 * $ bin/cake migrations rollback
 */
class Base extends AbstractMigration {
    /**
     * Migrate Up.
     */
    public function up()
    {
        // TABELAS DO SAC (Sistema de Autenticação Centralizada)
        $this->table('sistemas')
            ->addColumn('nome',         'string', ['default' => '-', 'limit' => 100, 'null' => false])
            ->addColumn('ativo',        'boolean', ['default' => true, 'null' => false])
            ->create();

        $this->table('municipios')
            ->addColumn('nome',         'string', ['default' => '-', 'limit' => 100, 'null' => false])
            ->addColumn('uf',           'string', ['default' => '-', 'limit' => 2, 'null' => false])
            ->addColumn('codi_estd',    'string', ['default' => '-', 'limit' => 2,'null' => false])
            ->addColumn('desc_estd',    'string', ['default' => '-', 'limit' => 50, 'null' => false])
            ->addIndex(['uf', 'nome'])
            ->create();

        $this->table('unidades')
            ->addColumn('nome',         'string', ['default' => '-', 'limit' => 100, 'null' => false])
            ->addColumn('cnpj',         'double', ['default' => 0, 'limit'=>14, 'null' => false])
            ->addColumn('ativo',        'boolean',['default' => true, 'null' => false])
            ->addIndex(['cnpj'], ['unique'=>true])
            ->create();

        $this->table('usuarios')
            ->addColumn('nome',         'string', ['default' => '', 'limit' => 100, 'null' => false])
            ->addColumn('email',        'string', ['default' => '', 'limit' => 100, 'null' => false])
            ->addColumn('senha',        'string', ['default' => '', 'limit' => 100, 'null' => false])
            ->addColumn('ativo',        'boolean', ['default' => true, 'null' => false])
            ->addColumn('ultimo_acesso', 'timestamp', ['default' => date('Y-m-d H:i:s'), 'null' => false])
            ->addColumn('municipio_id', 'integer', ['default' => 3106200, 'limit' => 11, 'null' => false])
            ->addIndex(['municipio_id'])
            ->addIndex(['ativo'])
            ->create();
        $this->table('usuarios')
            ->addForeignKey('municipio_id', 'municipios', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->update();

        // TABELAS DO SISTEMA.

        $this->table('perfis')
            ->addColumn('nome',         'string', ['default' => '-', 'limit' => 100, 'null' => false])
            ->addColumn('ativo',        'boolean', ['default' => true, 'null' => false])
            ->addColumn('sistema_id',   'integer', ['default' => 1, 'limit' => 11, 'null' => false])
            ->create(['nome']);
        $this->table('perfis')
            ->addForeignKey('sistema_id', 'sistemas', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->update();
            
        $this->table('recursos')
            ->addColumn('url',          'string',   ['default'=>'', 'limit'=>100, 'null'=>false])
            ->addColumn('titulo',       'string',   ['default'=>'', 'limit'=>100, 'null'=>false])
            ->addColumn('menu',         'string',   ['default'=>'', 'limit'=>100, 'null'=>false])
            ->addColumn('ativo',        'boolean',  ['default' => true, 'null' => false])
            ->addColumn('sistema_id',   'integer', ['default' => 1, 'limit' => 11, 'null' => false])
            ->addIndex(['url'])
            ->addIndex(['ativo'])
            ->create();
        $this->table('recursos')
            ->addForeignKey('sistema_id', 'sistemas', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->update();

        $this->table('papeis')
            ->addColumn('sistema_id',   'integer',  ['limit'=> 11, 'null' => false])
            ->addColumn('unidade_id',   'integer',  ['limit'=> 11, 'null' => false])
            ->addColumn('usuario_id',   'integer',  ['limit'=> 11, 'null' => false])
            ->addColumn('perfil_id',    'integer',  ['limit'=> 11, 'null' => false])
            ->create();
        $this->table('papeis')
            ->addForeignKey('sistema_id',   'sistemas', 'id',   ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('usuario_id',   'usuarios', 'id',   ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('unidade_id',   'unidades', 'id',   ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('perfil_id',    'perfis',   'id',   ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->update();

        $this->table('papeis_recursos')
            ->addColumn('papel_id',  'integer',  ['limit' => 11])
            ->addColumn('recurso_id', 'integer',  ['limit' => 11])
            ->create();
        $this->table('papeis_recursos')
            ->addForeignKey('papel_id',   'papeis',   'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('recurso_id', 'recursos', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->update();

       $this->table('auditorias')
            ->addColumn('ip',               'string',   ['default'=>'', 'limit'=>15,  'null'=>false])
            ->addColumn('motivo',           'string',   ['default'=>'', 'limit'=>50, 'null'=>false])
            ->addColumn('descricao',        'string',   ['default'=>'', 'limit'=>250, 'null'=>false])
            ->addColumn('usuario_id',       'integer',  ['default'=>0,'null'=>false])
            ->addColumn('sistema_id',       'integer',  ['default'=>0,'null'=>false])
            ->addColumn('data',             'timestamp',['default'=>date('Y-m-d H:i:s'), 'null' => false])
            ->create();
        $this->table('auditorias')
            ->addIndex(['motivo'])
            ->addForeignKey('usuario_id',   'usuarios', 'id',   ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('sistema_id',   'sistemas', 'id',   ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->update();

        // atualizando os dados.
        $this->updateSistemas();
        $this->updateMunicipios();
        $this->updatePerfis();
        $this->updateUnidades();
        $this->updateUsuarios();
        $this->updateRecursos();
        $this->updatePapeis();
        $this->updatePapeisRecursos();
        $this->updateAuditorias();

        echo "\n";
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        //$this->table('usuarios')->dropForeignKey('municipio_id')->save();
        $this->table('papeis_recursos')->drop()->save();
        $this->table('auditorias')->drop()->save();
        $this->table('papeis')->drop()->save();
        $this->table('usuarios')->drop()->save();
        $this->table('municipios')->drop()->save();      
        $this->table('recursos')->drop()->save();
        $this->table('perfis')->drop()->save();
        $this->table('unidades')->drop()->save();
        $this->table('sistemas')->drop()->save();

        echo "\n";
    }

    /**
     * Atualiza a tabela de sistemas
     *
     * @return  void
     */
    private function updateSistemas()
    {
        $this->execute('delete from sistemas');
        $table = $this->table('sistemas');

        $data   = [];
        $data[] = ['id'=>1, 'nome' => SISTEMA];

        $table->insert($data)->save();
    }

    /**
     */
    private function updateAuditorias()
    {
        $this->execute('delete from auditorias');
        $table      = $this->table('auditorias');
        $data       = [];
        $data[]     = ['ip'=>gethostbyname(gethostname()), 'motivo'=>'instalacao', 'descricao'=>'A instalação do sistema '.SISTEMA.' foi realizada', 'usuario_id'=>1, 'sistema_id'=>1, 'data'=>date('Y-m-d H:i:s')];
        if ( strpos(strtolower(@$_SERVER['OS']), 'windows') > -1 )
        {
            $data[0]['descricao'] = utf8_decode($data[0]['descricao']);
        }

        $table->insert($data)->save();
    }

    /**
     * Atualiza a atabela de usuaŕios. Cria o usuário administrador.
     *
     * @return  void
     */
    private function updateUsuarios()
    {
        $this->execute('delete from usuarios');
        $table      = $this->table('usuarios');
        $senhaAdmin = (new DefaultPasswordHasher)->hash('admin1234');

        $data       = [];
        $data[]     = ['nome'=>'Administrador '.SISTEMA, 'email'=>'admin@admin.com.br', 'senha'=>$senhaAdmin];

        $table->insert($data)->save();
    }

    /**
     * Atualiza a tabela de papeis
     *
     * @return  void
     */
    private function updatePerfis()
    {
        $this->execute('delete from perfis');
        $table = $this->table('perfis');

        $data   = [];
        $data[] = ['nome'=>'ADMINISTRADOR'];
        $data[] = ['nome'=>'SUPERVISOR'];
        $data[] = ['nome'=>'VISITANTE'];

        $table->insert($data)->save();
    }

    /**
     * Atualiza a tabela de papeis
     *
     * @return  void
     */
    private function updateUnidades()
    {
        $this->execute('delete from unidades');
        $table = $this->table('unidades');

        $data   = [];
        $data[] = ['nome'=> strtoupper('UNIDADE '.SISTEMA), 'cnpj'=> 12345678901234];
        $data[] = ['nome'=> strtoupper('UNIDADE SECUNDÁRIA '.SISTEMA), 'cnpj'=> 12345678901235];

        // se estais rodando da kaka do windows, coisa que não recomendo.
        if ( strpos(strtolower(@$_SERVER['OS']), 'windows') > -1 )
        {
            foreach($data as $_l => $_arrFields)
            {
                $data[$_l]['nome'] = utf8_decode($data[$_l]['nome']);
            }
        }

        $table->insert($data)->save();
    }

    /**
     * Atualiza a tabela de municipios
     *
     * @return  void
     */
    private function updateMunicipios()
    {
        $data   = [];
        $arq    = ROOT . DS . 'config' . DS . 'schema' . DS . 'municipios.csv';
        $csvFile= file($arq);
        foreach ($csvFile as $_l => $_linha)
        {
            if ($_l)
            {
                $arrCmps = str_getcsv($_linha);
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                {
                    $arrCmps[1] = utf8_decode($arrCmps[1]);
                    $arrCmps[4] = utf8_decode($arrCmps[4]);
                }
                $data[] = 
                [
                    'id'        => (int)$arrCmps[0], 
                    'nome'      => trim($arrCmps[1]), 
                    'uf'        => trim($arrCmps[2]), 
                    'codi_estd' => (int)$arrCmps[3], 
                    'desc_estd' => trim($arrCmps[4])
                ];
            }
        }
        $this->execute('delete from municipios');
        $table = $this->table('municipios');
        $table->insert($data)->save();
    }

    /**
     * Atualiza a tabela de recursos
     *
     * @return  void
     */
    private function updateRecursos()
    {
        $this->execute('delete from recursos');
        $table = $this->table('recursos');

        $data   = [];
        $data[] = ['url'=>'/painel/index',          'titulo'=> 'Página inicial'];
        $data[] = ['url'=>'/painel/info',           'titulo'=>'Informações do Usuário'];

        $data[] = ['url'=>'/usuarios/index',        'menu'=>'Cadastros', 'titulo'=>'Usuários'];
        $data[] = ['url'=>'/municipios/index',      'menu'=>'Cadastros', 'titulo'=>'Municípios'];
        $data[] = ['url'=>'/auditorias/index',      'menu'=>'Cadastros', 'titulo'=>'Auditorias'];

        $data[] = ['url'=>'/ferramentas/limpar-cache', 'menu'=>'Ferramentas', 'titulo'=>'Limpar Cache'];
        $data[] = ['url'=>'/ferramentas/recarregar-permissoes', 'menu'=>'Ferramentas', 'titulo'=>'Recarregar as Permissões'];
        $data[] = ['url'=>'/ferramentas/trocar-papel', 'menu'=>'Ferramentas', 'titulo'=>'Trocar Papel'];

        $data[] = ['url'=>'/relatorios/index',      'titulo'=>'Usuários'];
        $data[] = ['url'=>'/relatorios/usuarios',   'menu'=>'Relatórios', 'titulo'=>'Usuários'];

        $data[] = ['url'=>'/ajuda/manual',          'menu'=>'Ajuda', 'titulo'=>'Manual'];
        $data[] = ['url'=>'/ajuda/sobre',           'menu'=>'Ajuda', 'titulo'=>'Sobre'];

        // se estais rodando da kaka do windows, coisa que não recomendo.
        if ( strpos(strtolower(@$_SERVER['OS']), 'windows') > -1 )
        {
            foreach($data as $_l => $_arrFields)
            {
                $data[$_l]['titulo']= utf8_decode($data[$_l]['titulo']);
                if ( isset($_arrFields['menu']) )
                {
                    $data[$_l]['menu']  = utf8_decode($data[$_l]['menu']);
                }
            }
        }

        $table->insert($data)->save();
    }

    /**
     * Atualiza as vinculações.
     *
     * @return  void
     */
    private function updatePapeis()
    {
        $this->execute('delete from papeis');
        $table = $this->table('papeis');

        $data   = [];
        $data[] = ['sistema_id'=>1, 'usuario_id'=>1, 'unidade_id'=>1, 'perfil_id'=>1];
        $data[] = ['sistema_id'=>1, 'usuario_id'=>1, 'unidade_id'=>1, 'perfil_id'=>2];
        $data[] = ['sistema_id'=>1, 'usuario_id'=>1, 'unidade_id'=>2, 'perfil_id'=>1];
        $data[] = ['sistema_id'=>1, 'usuario_id'=>1, 'unidade_id'=>2, 'perfil_id'=>3];

        $table->insert($data)->save();
    }

    /**
     * Atualiza as updatePapeisRecursos.
     *
     * @return  void
     */
    private function updatePapeisRecursos()
    {
        $arrRecursos    = $this->fetchAll('select id, menu, url from recursos order by 1');
        $arrPerfis      = $this->fetchAll('select id from papeis order by 1');
        $naoVisitante   = ['/ferramentas/limpar-cache'];

        $data           = [];
        $l              = 0;
        foreach($arrRecursos as $_l => $_arrFields)
        {
            foreach($arrPerfis as $_l2 => $_arrFields2)
            {
                if ( $_arrFields2['id'] == 3 && in_array($_arrFields['url'], $naoVisitante) )
                {
                    continue;
                }

                $data[$l]['recurso_id'] = $_arrFields['id'];
                $data[$l]['papel_id']  = $_arrFields2['id'];
                $l++;
            }
        }

        $table  = $this->table('papeis_recursos');
        $table->insert($data)->save();
    }
}
