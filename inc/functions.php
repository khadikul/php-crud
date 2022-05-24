<?php

define("DB_NAME", "/xamp/htdocs/Crud/data/db.txt");

function seed(){
    $data = array(
        [
            'id'     => 1,
            'fname'  => 'khadikul',
            'lname'  => 'Islam',
            'roll'   => 1,
        ],
        
        [
            'id'     => 2,
            'fname'  => 'Airen',
            'lname'  => 'Islam',
            'roll'   => 2,
        ],
        
        [
            'id'     => 3,
            'fname'  => 'Jhon',
            'lname'  => 'Doe',
            'roll'   => 78,
        ],
    );
    $serializeData = serialize($data);
    file_put_contents(DB_NAME, $serializeData, LOCK_EX);
}

function genarateRepot(){

    $serializeData = file_get_contents(DB_NAME);
    $unSeraialzeData = unserialize($serializeData);

    ?>
        <table class="table table-striped table-hover table-bordered">
            <tr>
                <th>Name</th>
                <th class="text-center">Roll</th>
                <th class="text-center">Acton</th>
            </tr>
            <?php
                foreach($unSeraialzeData as $student){
                    ?>
                        <tr>
                            <td class="text-capitalize"><?php printf("%s %s", $student['fname'], $student['lname']); ?></td>
                            <td class="text-center"><?php printf("%s", $student['roll']); ?></td>
                            <td class="text-center"><?php printf('<a href="index.php?task=edit&id=%s">Edit</a> | <a class="deleteData" href="index.php?task=delete&id=%s">Delete</a>', $student['id'], $student['id']); ?></td>
                        </tr>
                    <?php
                } 
            ?>
        </table>
    <?php
}

function add_student($fname, $lname, $roll){
    $serializeData = file_get_contents(DB_NAME);
    $unSeraialzeData = unserialize($serializeData);
    foreach($unSeraialzeData as $_studentData){
        if($_studentData['roll'] == $roll){
            $found = true;
            break;

        }
    }
    if(!$found){
        $newId = getNewId($unSeraialzeData);

        $student = array(
            'id'     => $newId,
            'fname'  => $fname,
            'lname'  => $lname,
            'roll'   => $roll,
        );

        array_push($unSeraialzeData, $student);
        $serializeData = serialize($unSeraialzeData);
        file_put_contents(DB_NAME, $serializeData, LOCK_EX);
        return true;
    }
    return false;
}

function get_student($id){
    $serializeData = file_get_contents(DB_NAME);
    $unSeraialzeData = unserialize($serializeData);
    foreach($unSeraialzeData as $studentData){
        if($studentData['id'] == $id){
            return $studentData;
        }
    }

    return false;
}

function updateStudent($id, $fname, $lname, $roll){
    $found = false;
    $serializeData = file_get_contents(DB_NAME);
    $unSeraialzeData = unserialize($serializeData);

    foreach($unSeraialzeData as $_studentData){
        if($_studentData['roll'] == $roll && $_studentData['id'] != $id){
            $found = true;
            break;

        }
    }

    if(!$found){

        $unSeraialzeData[$id-1]['fname'] = $fname;
        $unSeraialzeData[$id-1]['lname'] = $lname;
        $unSeraialzeData[$id-1]['roll'] = $roll;

        $serializeData = serialize($unSeraialzeData);
        file_put_contents(DB_NAME, $serializeData, LOCK_EX);

        return true;
    }

    return false;
}

function deleteStudnet($id){
    $serializeData = file_get_contents(DB_NAME);
    $unSeraialzeData = unserialize($serializeData);

    foreach($unSeraialzeData as $offset=>$studentData){
        if($studentData['id'] == $id){
            unset($unSeraialzeData[$offset]);
        }
    }


    $serializeData = serialize($unSeraialzeData);
    file_put_contents(DB_NAME, $serializeData, LOCK_EX);
}

function getNewId($unSeraialzeData){
    $maxId = max(array_column($unSeraialzeData, 'id'));
    return $maxId+1;
}