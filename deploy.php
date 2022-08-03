<?php
namespace Deployer;

require 'recipe/laravel.php';

set('application', 'test for CICD');
set('ssh_multiplexing', true);

// Config

set('repository', 'https://github.com/m-usama-hub/testForCICD.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

task('deploy:secrets', function () {
    file_put_contents(__DIR__ . '/.env', getenv('DOT_ENV'));
    upload('.env', get('deploy_path') . '/shared');
});

// Hosts

host('custom-dev.onlinetestingserver.com')
    ->set('hostname','45.63.58.248')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/www/testForCICD');

// Hooks

after('deploy:failed', 'deploy:unlock');

desc('Deploy the application');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync',
    'deploy:secrets',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    // 'artisan:migrate',
    'artisan:queue:restart',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);
