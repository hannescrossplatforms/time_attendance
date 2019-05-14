<?php 
 
class Heatmap{

	public static function getHeatmapscanners() {
		
        $cordinates = array( 'alshaya-mothercare' =>array(
                            array('x'=>'800','y'=>'100','scanner'=>'MCARE_1'),
                            array('x'=>'190','y'=>'80','scanner'=>'MCARE_2'),
                            array('x'=>'330','y'=>'180','scanner'=>'MCARE_3'),
                            array('x'=>'510','y'=>'100','scanner'=>'MCARE_4'),
                            array('x'=>'540','y'=>'370','scanner'=>'MCARE_5'),
                            array('x'=>'690','y'=>'10','scanner'=>'MCARE_6'),
                            array('x'=>'250','y'=>'100','scanner'=>'MCARE_7'),
                            array('x'=>'220','y'=>'70','scanner'=>'MCARE_8'),
                            array('x'=>'160','y'=>'50','scanner'=>'MCARE_9'),
                            array('x'=>'690','y'=>'40','scanner'=>'MCARE_10')/*,
                            array('x'=>'60','y'=>'90','scanner'=>'MCARE_11'),
                            array('x'=>'75','y'=>'10','scanner'=>'MCARE_12')*/
                            ),
                            'alshaya-victorias-secret' => array(
                            array('x'=>'700','y'=>'100','scanner'=>'VS_1'),
                            array('x'=>'290','y'=>'280','scanner'=>'VS_2'),
                            array('x'=>'380','y'=>'180','scanner'=>'VS_3'),
                            array('x'=>'510','y'=>'400','scanner'=>'VS_4'),
                            array('x'=>'540','y'=>'870','scanner'=>'VS_5'),
                            array('x'=>'390','y'=>'10','scanner'=>'VS_6'),
                            array('x'=>'650','y'=>'100','scanner'=>'VS_7'),
                            array('x'=>'320','y'=>'70','scanner'=>'VS_8')
                            ) 
                            );
    	return $cordinates;
	}

}