<?php
namespace Deployer;
require 'recipe/common.php';

set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('repository', 'git@github.com:Arkanius/deploy-test.git');

server('production', '54.70.195.106')
	->user('ubuntu')
	->pemFile('~/Downloads/sufocoKey.pem')
	->set('deploy_path', '/var/www/test-deploy');

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
	run('sudo service php7.1-fpm restart');
});

after('deploy:symlink', 'php-fpm:restart');

desc('Deploy your project');
task('deploy', [
	'deploy:prepare',
	'deploy:lock',
	'deploy:release',
	'deploy:update_code',
	'deploy:shared',
	'deploy:writable',
	'deploy:vendors',
	'deploy:clear_paths',
	'deploy:symlink',
	'deploy:unlock',
	'cleanup',
	'success',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');