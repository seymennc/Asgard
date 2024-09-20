<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <link rel="stylesheet" href="<?= public_path('css/exception.css') ?>">
</head>
<body>

    <div class="card bg-dark text-white rounded-0">
        <div class="card-body">
            <div class="col-md-2 text-center bg-darken"><p class="card-text"><pre><?= $error['type'] ?></pre></p></div>
            <p class="card-text"><?= $error['message'] . ' in ' . $error['file'] . ' line ' . $error['line'] ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <?php
                foreach ($error['trace'] as $trace)
                    echo '<div class="card-body bg-darken rounded-0 ">
                                <p class="card-text text-white h6">' . $trace['class'] . '</p>
                                <p class="card-text text-white">' . $trace['file'] . ':' . $trace['line'] . '</p>
                        </div><div class="line"></div>';

                ?>
            </div>
        </div>
        <div class="col-md-9">
           <div class="row">
               <div class="col-md-12">
                   <div class="card rounded-0 bg-dark-md">
                       <div class="card-body">
                           <?php
                           echo '<pre class="card-text text-white">';
                           echo '<p class="card-text text-white mx-3">' . $error['file'] . ' line ' . $error['line'] . '</p>';
                           echo '<code class="language-php">';
                           $file = $error['file'];
                           $line = $error['line'];

                           $lines = file($file);

                           $start = max(0, $line - 16);
                           $end = min(count($lines) - 1, $line + 14);
                           for ($i = $start; $i <= $end; $i++) {
                               echo ($i + 1) . ': ' . htmlspecialchars($lines[$i]);
                           }
                           echo '</code>';
                           echo '</pre>';

                           echo '</div>';
                           ?>
                   </div>
               </div>
               <div class="col-md-12 bg-danger">
                   <div class="card rounded-0">
                       <div class="card-body mt-5">
                            <div class="mb-5">
                                <p class="card-text h4">Environments & Details</p>
                                <p class="fw-bolder mt-4">Environments: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::environments()->environment; ?></code></p>
                                <p class="fw-bolder">Framework Version: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::environments()->framework_version ?></code></p>
                                <p class="fw-bolder">App Name: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::environments()->project_name ?></code></p>
                                <p class="fw-bolder">Base Path: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::environments()->base_path ?></code></p>
                                <p class="fw-bolder">DEBUG: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::environments()->debug ?></code></p>
                            </div>
                           <div class="line"></div>
                           <div class="mb-5 mt-5">
                               <p class="card-text h4">Request</p>
                               <p class="fw-bolder mt-4">Request Method: <code class="text-dark fw-normal"><?= strtoupper(\Asgard\system\Request::getMethod()) ?></code></p>
                               <p class="fw-bolder">Request URL: <code class="text-dark fw-normal"><?= \Asgard\system\Request::getUrl() ?></code></p>
                               <p class="fw-bolder">Client IP: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::server()->client_ip ?></code></p>
                               <p class="fw-bolder">Client Agent: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::server()->client_agent ?></code></p>
                               <p class="fw-bolder">GET DATA: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getData()) ?></code></p>
                               <p class="fw-bolder">POST DATA: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::postData());?></code></p>
                               <p class="fw-bolder">Query Parameters: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getQueryParams());?></code></p>
                               <p class="fw-bolder">Files: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getFiles());?></code></p>
                               <p class="fw-bolder">Authorization Token: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getAuthorizationToken());?></code></p>
                               <p class="fw-bolder">Content Type: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getContentType());?></code></p>
                               <p class="fw-bolder">Json Payload: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getJsonPayload());?></code></p>

                           </div>
                            <div class="line"></div>
                            <div class="mt-5 mb-5">
                                <p class="card-text h4">Session</p>

                                <p class="fw-bolder mt-4">Session: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getSession()); ?></code></p>
                            </div>
                           <div class="line"></div>
                           <div class="mb-5 mt-5">
                               <p class="card-text h4">Cookies</p>
                               <p class="fw-bolder">
                               <ul class="list-unstyled">
                                   <?php
                                   foreach (\Asgard\system\Request::getCookies() as $cookie) {
                                       echo '<li><strong>' . htmlspecialchars($cookie['name']) . ':</strong> ' . htmlspecialchars($cookie['value']) . '</li>';
                                   }
                                   ?>
                               </ul>
                               </p>

                           </div>
                            <div class="line"></div>
                           <div class="mb-5 mt-5">
                               <p class="card-text h4">Server</p>
                               <p class="fw-bolder mt-4">PHP Version: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::server()->phpVersion ?></code></p>
                               <p class="fw-bolder">Server Software: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::server()->server_software ?></code></p>
                               <p class="fw-bolder">Server Name: <code class="text-dark fw-normal"><?= \App\Utilities\SystemInfo::server()->server_name ?></code></p>
                               <p class="fw-bolder">Memory Usage: <code class="text-dark fw-normal"><?php print_r(\App\Utilities\SystemInfo::server()->memory_usage) ?></code></p>

                               <p class="fw-bolder">Operating System: <code class="text-dark fw-normal"><?php print_r(\App\Utilities\SystemInfo::server()->operating_system) ?></code></p>
                               <p class="fw-bolder">Max Execution Time: <code class="text-dark fw-normal"><?php print_r(\App\Utilities\SystemInfo::server()->max_execution_time) ?></code></p>
                               <p class="fw-bolder">Uptime: <code class="text-dark fw-normal"><?php print_r(\App\Utilities\SystemInfo::server()->uptime) ?></code></p>
                               <p class="fw-bolder">CPU Usage: <code class="text-dark fw-normal"><?php print_r(\App\Utilities\SystemInfo::server()->cpu_usage) ?></code></p>
                               <p class="fw-bolder">Active Connections: <code class="text-dark fw-normal"><?php print_r(\App\Utilities\SystemInfo::server()->active_connections) ?></code></p>
                               <p class="fw-bolder">Loaded Extensions: <code class="text-dark fw-normal"><?php print_r(\App\Utilities\SystemInfo::server()->loaded_extensions) ?></code></p>
                           </div>
                            <div class="line"></div>
                           <div class="mb-5 mt-5">
                               <p class="card-text h4">Files</p>
                               <p class="fw-bolder mt-4">Files: <code class="text-dark fw-normal"><?= $error['file'] ?></code></p>
                               <p class="fw-bolder">Message: <code class="text-dark fw-normal"><?= $error['message'] ?></code></p>
                               <p class="fw-bolder">Line: <code class="text-dark fw-normal"><?= $error['line'] ?></code></p>
                               <p class="fw-bolder">Type: <code class="text-dark fw-normal"><?= $error['type'] ?></code></p>
                               <p class="fw-bolder">Status Code: <code class="text-dark fw-normal"><?= $error['code'] ?></code></p>
                               <p class="fw-bolder">Trace: <code class="text-dark fw-normal"><?php print_r($error['trace']) ?></code></p>

                           </div>
                            <div class="line"></div>
                           <div class="mb-5 mt-5">
                               <p class="card-text h4">Headers</p>
                               <p class="fw-bolder mt-4">Headers: <code class="text-dark fw-normal"><?php print_r(\Asgard\system\Request::getHeaders());?></code></p>

                           </div>
                            <div class="line"></div>
                           <div class="mb-5 mt-5">
                               <p class="card-text h4">Config</p>
                               <p class="fw-bolder mt-4">Timezone: <code class="text-dark fw-normal"><?= config('app.timezone'); ?></code></p>
                               <p class="fw-bolder">Locale: <code class="text-dark fw-normal"><?= config('app.locale'); ?></code></p>
                               <p class="fw-bolder">Fallback Locale: <code class="text-dark fw-normal"><?= config('app.fallback_locale'); ?></code></p>
                               <p class="fw-bolder">Encoding: <code class="text-dark fw-normal"><?= config('app.encoding'); ?></code></p>
                               <p class="fw-bolder">Date Format: <code class="text-dark fw-normal"><?= config('app.date_format'); ?></code></p>
                               <p class="fw-bolder">Database Connection: <code class="text-dark fw-normal"><?= config('app.db_connection'); ?></code></p>
                           </div>
                            <div class="line"></div>

                       </div>
                   </div>
               </div>
           </div>
        </div>
    </div>
</div>



<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/go.min.js"></script>

    <script>hljs.highlightAll();</script>
</body>
</html>