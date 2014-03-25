<?php


/* Queue Settings, Queue Names must not have whitespace, or HTML ids fail! */
$conf['queue']['blacklight']['cpu']         = 384;
$conf['queue']['blacklight']['memory']      = 2048;
$conf['queue']['blacklight']['nodes']       = 4;


$conf['queue']['default']['cpu']            = 8;
$conf['queue']['default']['memory']         = 24;
$conf['queue']['default']['nodes']          = 25;

$conf['queue']['default']['default']       = 1; 
        
$conf['queue']['largememory']['cpu']       = 24;
$conf['queue']['largememory']['memory']    = 1024;
$conf['queue']['largememory']['nodes']     = 1;



/* Recommended and Optional Settings we want the user to specifyn */
$conf['settings']['recommended']            = array('M','N','d');
$conf['settings']['optional']               = array('e','A');


/* Torque Qsub Settings */
/* Special case should have the parameter as its value, and you must handle the logic in a javascript function */
$conf['param']['a']['description']          = 'Date / Time';
$conf['param']['a']['example']              = '02/02/1989';

$conf['param']['A']['description']          = 'Project';
$conf['param']['A']['example']              = 'beeSequencing101';

$conf['param']['b']['description']          = 'pbs_server contact timeout ';
$conf['param']['b']['example']              = 'example';

$conf['param']['c']['description']          = 'description';
$conf['param']['c']['example']              = 'example';

$conf['param']['C']['description']          = 'description';
$conf['param']['C']['example']              = 'example';

$conf['param']['d']['description']          = 'Working directory path';
$conf['param']['d']['example']              = '/home/n-z/yournetid/experimentX/run1';

$conf['param']['D']['description']          = 'description';
$conf['param']['D']['example']              = 'example';

$conf['param']['e']['description']          = 'Standard error output file';
$conf['param']['e']['example']              = 'list_of_errors.err';


$conf['param']['f']['description']          = 'Faul Tolerant';
$conf['param']['f']['example']              = 'example';


$conf['param']['h']['description']          = 'description';
$conf['param']['h']['example']              = 'example';

$conf['param']['I']['description']          = 'Use interactive mode';
$conf['param']['I']['example']              = 'example';

$conf['param']['j']['description']          = 'j oe';
$conf['param']['j']['example']              = 'example';

$conf['param']['k']['description']          = 'k oe';
$conf['param']['j']['example']              = 'example';

$conf['param']['l']['description']          = 'resource list';
$conf['param']['l']['example']              = 'ppn:5;etc';

$conf['param']['M']['description']          = 'Email';
$conf['param']['M']['example']              = 'netid@illinois.edu';
$conf['param']['M']['special_case']         = 'M';

$conf['param']['m']['description']          = '';
$conf['param']['m']['example']              = '';
$conf['param']['m']['special_case']         = 'm';



$conf['param']['N']['description']          = 'Job Name';
$conf['param']['N']['example']              = 'example101job';

$conf['param']['o']['description']          = 'description';
$conf['param']['o']['example']              = 'example';

$conf['param']['p']['description']          = 'description';
$conf['param']['p']['example']              = 'example';

$conf['param']['P']['description']          = 'description';
$conf['param']['P']['example']              = 'example';

$conf['param']['q']['description']          = 'queue';
$conf['param']['q']['example']              = 'example';

$conf['param']['r']['description']          = 'description';
$conf['param']['r']['example']              = 'example';

$conf['param']['S']['description']          = 'description';
$conf['param']['S']['example']              = 'example';

$conf['param']['t']['description']          = 'description';
$conf['param']['t']['example']              = 'example';

$conf['param']['T']['description']          = 'description';
$conf['param']['T']['example']              = 'example';

$conf['param']['u']['description']          = 'description';
$conf['param']['u']['example']              = 'example';

$conf['param']['W']['description']          = 'description';
$conf['param']['W']['example']              = 'example';

$conf['param']['v']['description']          = 'description';
$conf['param']['v']['example']              = 'example';

$conf['param']['V']['description']          = 'description';
$conf['param']['V']['example']              = 'example';

$conf['param']['x']['description']          = 'description';
$conf['param']['x']['example']              = 'example';

$conf['param']['X']['description']          = 'use X11 Display';
$conf['param']['X']['example']              = 'example';

$conf['param']['z']['description']          = 'description';
$conf['param']['z']['example']              = 'example';


?>
