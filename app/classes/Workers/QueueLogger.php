<?php

namespace Workers;

use Obullo\Queue\Job,
    Obullo\Queue\JobInterface,
    Obullo\QueueLogger\JobHandler\JobHandlerFile,
    Obullo\QueueLogger\JobHandler\JobHandlerMongo,
    Obullo\QueueLogger\JobHandler\JobHandlerEmail;

/**
 * Queue Logger
 *
 * @category  Queue
 * @package   QueueLogger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/queue
 */
Class QueueLogger implements JobInterface
{
    /**
     * Container
     * 
     * @var object
     */
    public $c;

    /**
     * Environment
     * 
     * @var string
     */
    public $env;

    /**
     * Constructor
     * 
     * @param object $c   container
     * @param string $env environments
     */
    public function __construct($c, $env)
    {
        $this->c = $c;
        $this->env = $env;
        $this->config = $this->c->load('config')['log'];
    }

    /**
     * Fire the job
     * 
     * @param Job   $job  object
     * @param array $data data array
     * 
     * @return void
     */
    public function fire(Job $job, $data)
    {
        $exp = explode('.', $job->getName());  // File, Mongo, Email ..
        $handlerName = ucfirst(end($exp));
        $JobHandlerName = strtolower($handlerName);

        switch ($JobHandlerName) {
        case 'file':
            $handler = new JobHandlerFile($this->c, $this->config);
            break;
        case 'email':
            $handler = new JobHandlerEmail(
                $this->c,
                $this->c->load('service/mailer'),
                array(
                    'from' => '<noreply@example.com> Server Admin',
                    'to' => 'obulloframework@gmail.com',
                    'cc' => '',
                    'bcc' => '',
                    'subject' => 'Server Logs',
                    'message' => 'Detailed logs here --> <br /> %s',
                    'format' => array(
                        'context' => 'array',  // json
                        'extra'   => 'array'   // json
                    ),
                )
            );
            break;
        case 'mongo':
            $handler = new JobHandlerMongo(
                $this->c,
                $this->c->load('service/provider/mongo', 'db'),
                array(
                    'database' => 'db',
                    'collection' => 'logs',
                    'save_options' => null,
                    'format' => array(
                        'context' => 'array',  // json
                        'extra'   => 'array'   // json
                    ),
                )
            );
            break;
        default:
            $handler = null;
            break;
        }
        if ($handler != null) {

            $formatted = $handler->format($this->config['format']['date'], $data);  // Do job
            print_r($formatted);
            $handler->write($formatted);  // Do job
            $handler->close();

            $job->delete();  // Delete job from queue
        }
    }

}

/* End of file QueueLogger.php */
/* Location: .app/classes/Workers/QueueLogger.php */