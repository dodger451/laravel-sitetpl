<?php

namespace Sitetpl\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadAccessLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accesslog:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read accesslog and matches with routes';

    //protected static $defaultFormat = '%h %l %u %t "%r" %>s %b';
    protected static $defaultFormat = '%a - %u %t "%m %c %e" %>s %b "%U" ".*"';
    protected $pcreFormat;
    protected $patterns = [
        '%%' => '(?P<percent>\%)',
        '%a' => '(?P<remoteIp>)',
        '%A' => '(?P<localIp>)',
        '%h' => '(?P<host>[a-zA-Z0-9\-\._:]+)',
        '%l' => '(?P<logname>(?:-|[\w-]+))',
        '%m' => '(?P<requestMethod>OPTIONS|GET|HEAD|POST|PUT|DELETE|TRACE|CONNECT|PATCH|PROPFIND)',
        '%p' => '(?P<port>\d+)',
        '%e' => '(?P<requestVersion>(?:HTTP/1.(?:0|1))|-|)',
        '%c' => '(?P<requestUri>(?:.+?))',
        '%r' => '(?P<request>(?:(?:[A-Z]+) .+? HTTP/1.(?:0|1))|-|)',
        '%t' => '\[(?P<time>\d{2}/(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/\d{4}:\d{2}:\d{2}:\d{2} (?:-|\+)\d{4})\]',
        '%u' => '(?P<user>(?:-|[\w-]+))',
        '%U' => '(?P<URL>.+?)',
        '%v' => '(?P<serverName>([a-zA-Z0-9]+)([a-z0-9.-]*))',
        '%V' => '(?P<canonicalServerName>([a-zA-Z0-9]+)([a-z0-9.-]*))',
        '%>s' => '(?P<status>\d{3}|-)',
        '%b' => '(?P<responseBytes>(\d+|-))',
        '%T' => '(?P<requestTime>(\d+\.?\d*))',
        '%O' => '(?P<sentBytes>[0-9]+)',
        '%I' => '(?P<receivedBytes>[0-9]+)',
        '\%\{(?P<name>[a-zA-Z]+)(?P<name2>[-]?)(?P<name3>[a-zA-Z]+)\}i' => '(?P<Header\\1\\3>.*?)',
        '%D' => '(?P<timeServeRequest>[0-9]+)',
    ];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $format = null;
        // Set IPv4 & IPv6 recognition patterns
        $ipPatterns = implode('|', array(
            'ipv4' => '(((25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9]))',
            'ipv6full' => '([0-9A-Fa-f]{1,4}(:[0-9A-Fa-f]{1,4}){7})', // 1:1:1:1:1:1:1:1
            'ipv6null' => '(::)',
            'ipv6leading' => '(:(:[0-9A-Fa-f]{1,4}){1,7})', // ::1:1:1:1:1:1:1
            'ipv6mid' => '(([0-9A-Fa-f]{1,4}:){1,6}(:[0-9A-Fa-f]{1,4}){1,6})', // 1:1:1::1:1:1
            'ipv6trailing' => '(([0-9A-Fa-f]{1,4}:){1,7}:)', // 1:1:1:1:1:1:1::
        ));
        $this->patterns['%a'] = '(?P<remoteIp>'.$ipPatterns.')';
        $this->patterns['%A'] = '(?P<localIp>'.$ipPatterns.')';
        $this->setFormat(self::getDefaultFormat());
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while ($line = fgets(STDIN)) {
            try {
                $urldecoded = parse_url($this->parse($line)->requestUri);
                $parameters = [];
                if(isset($urldecoded['query'])) {
                    parse_str($urldecoded['query'], $parameters);
                }
                $request = Request::create($urldecoded['path'], 'GET', $parameters);
                $route = app(\Illuminate\Routing\Router::class)->getRoutes()->match($request);
                echo $route->getActionName() . ' ' . ($route->getName()) . PHP_EOL;
            } catch (NotFoundHttpException $e){
                echo 'UNKNOWN' . PHP_EOL;
            } catch (\Exception $e) {
                echo 'ERROR' . PHP_EOL;
            }
        }
    }

    public static function getDefaultFormat()
    {
        return self::$defaultFormat;
    }


    public function setFormat($format)
    {
        // strtr won't work for "complex" header patterns
        // $this->pcreFormat = strtr("#^{$format}$#", $this->patterns);
        $expr = "#^{$format}$#";
        foreach ($this->patterns as $pattern => $replace) {
            $expr = preg_replace("/{$pattern}/", $replace, $expr);
        }
        $this->pcreFormat = $expr;
    }

    public function parse($line)
    {
        if (!preg_match($this->pcreFormat, $line, $matches)) {
            throw new \Exception("Error parsing line, check offset and limits");
        }
        $entry = new \stdClass();
        foreach (array_filter(array_keys($matches), 'is_string') as $key) {
            if ('time' === $key && true !== $stamp = strtotime($matches[$key])) {
                $entry->stamp = $stamp;
            }
            $entry->{$key} = $matches[$key];
        }
        return $entry;
    }
}
