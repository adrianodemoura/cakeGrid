<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Exception;

/**
 * ClearUsuarios command.
 */
class ClearUsuariosCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->loadModel('Usuarios');

        try
        {
            //$entity = $this->Usuarios->get(1);
            //$this->Usuarios->delete($entity);

            $totalExcluidos = $this->Usuarios->deleteAll( ['Usuarios.id >' => 1] );
            if ( $totalExcluidos>0) 
            {
                echo "\n$totalExcluidos registro(s) excluído(s) com sucesso !\n";
            } else
            {
                echo "\nNehum usuário foi excluiído !\n";
            }

        } catch (Exception $e)
        {
            echo "\n".$e->getCode().' - '.$e->getMessage()."\n\n";
        }        

        $totalUsuarios = $this->Usuarios->find()->count();

        echo "\nTotal Geral de Usuários: $totalUsuarios\n\n";
    }
}
