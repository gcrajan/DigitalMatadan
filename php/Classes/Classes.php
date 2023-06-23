<?php

function createelection($database_location,$user,$password,$hash)
{
    $conn_main = new mysqli($database_location,$user,$password);
    if($conn_main->connect_error){die("Error connecting to database");}

    if(!$conn_main->query("Create database Election".date("Y"))){die("Error creating election yeardb  or database exists");}
    $conn_main->close();
    
    $conn = new mysqli($database_location,$user,$password,"ElectionDb");
    if($conn->connect_error){die("Error connecting to database");}

    if(!$conn->query("create table MDVT".date("Y")."(
        VCMD varchar(128) not null unique,
        CMD varchar(128) not null,
        GPMD varchar(128) not null,
        WNMD varchar(128) not null,
        DISMD varchar(128) not null
    )")){die("Error creating table or table exists");}

    if(!$conn->query("Insert into Elections
        values('Election".date("Y")."',".date("Y").",'prep','".$hash."')")){die("Error creating table or table exists");}
    $conn->close();

    
    $conn_present = new mysqli($database_location,$user,$password,"Election".date("Y"));
    if($conn_present->connect_error){die("Error connecting to database");}

    if(!$conn_present->query("create table Gov_party(
        GPID int primary key auto_increment,
        GPname varchar(50),
        GPlogo varchar(100),
        Contact varchar(50),
        Leader varchar(50),
        Location varchar(50),
        Vote_count int default 0
    )")){die("Error creating table party or table exists");}

    if(!$conn_present->query("create table Candidate(
        CID int primary key auto_increment,
        Cname varchar(50),
        Clogo varchar(100),
        Election_area varchar(50),
        GPID int,
        Contact varchar(50),
        Location varchar(50),
        Vote_count int default 0,
        foreign key (GPID) references Gov_party(GPID)
    )")){die("Error creating table candidate or table exists");}    
    
    $conn_present->close();
}

function upload_pic($file_path,$file_name,$pic)
{
    if(!is_dir("images")){mkdir("images");}
    if(!is_dir("images/Candidate")){mkdir("images/Candidate");}
    if(!is_dir("images/Gov_party")){mkdir("images/Gov_party");}

    $imageFileType = strtolower(pathinfo($pic["name"],PATHINFO_EXTENSION));
    $target_file = $file_path. "/".$file_name.".".$imageFileType;
    if(!is_dir($file_path)){mkdir($file_path);}
    if(file_exists($target_file)){unlink($target_file);}
    if(getimagesize($pic["tmp_name"]) !== false) 
    {
        if(($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png") && $pic["size"]<2097152) 
        {
            move_uploaded_file($pic["tmp_name"], $target_file);
            return $target_file;
        } 
        else { echo"Error pic format not valid";return false; }
    } 
    else { echo"Error pic format not valid";return false; }
}

class Admin
{
    private $conn;
    private $conn_present;
    private $hash;
    function __construct($conn,$conn_present,$hash)
    {
        $this->conn_present = $conn_present;
        $this->conn = $conn;
        $this->hash =$hash;

    }

    function Add_candidate($Cname,$Clogo,$GPID,$Contact,$Location,$Election_Area)
    {
        if(!$this->conn_present->query("Insert into Candidate(Cname,GPID,Contact,Location,Election_area)
                values('".$Cname."',".$GPID.",'".$Contact."','".$Location."','".$Election_Area."')")){die("error inserting data");}
        $id=$this->conn_present->insert_id;

        $loc = upload_pic("images/Candidate/".$id,"logo",$Clogo);
        
        if($loc==false){die("Error uploading logo");}

        if(!$this->conn_present->query("Update Candidate set Clogo = '".$loc."' where CID = ".$id))
        {
            $this->conn_present->query("Delete from Candidate where CID = ".$id);
            die("Error uploading pic");
        }
    }
    function Add_party($GPname,$GPlogo,$Contact,$Leader,$Location)
    {
        if(!$this->conn_present->query("Insert into Gov_party(GPname,Contact,Leader,Location)
                values('".$GPname."',".$Contact.",'".$Leader."','".$Location."')")){die("error inserting data");}
        $id=$this->conn_present->insert_id;

        $loc = upload_pic("images/Gov_party/".$id,"logo",$GPlogo);
        if($loc==false){die("Error uploading logo");}

        if(!$this->conn_present->query("Update Gov_party set GPlogo = '".$loc."' where GPID = ".$id))
        {
            $this->conn_present->query("Delete from Gov_party where GPID = ".$id);
            die("Error uploading pic");
        }
    }
    function Edit_party($GPID,$GPname,$GPlogo,$Contact,$Leader,$Location)
    {
        if($GPlogo["name"]=="")
        {
            if(!$this->conn_present->query("Update Gov_party 
            set GPname='".$GPname."',
            Contact='".$Contact."',
            Leader='".$Leader."',
            Location='".$Location."'
            where GPID = ".$GPID))
            {die("Error uploading data might cause errors");}
        }
        else
        {
            $loc = upload_pic("images/Gov_party/".$GPID,"logo",$GPlogo);
            if($loc==false){die("Error uploading logo");}

            if(!$this->conn_present->query("Update Gov_party 
            set GPlogo = '".$loc."',
            GPname='".$GPname."',
            Contact='".$Contact."',
            Leader='".$Leader."',
            Location='".$Location."'
            where GPID = ".$GPID))
            {die("Error uploading data might cause errors");}
        }        
    }
    function Edit_candidate($CID,$Cname,$Clogo,$GPID,$Contact,$Location,$Election_Area)
    {
        if($Clogo["name"]=="")
        {
            if(!$this->conn_present->query("Update Candidate 
            set 
            Cname= '".$Cname."',
            GPID= ".$GPID.",
            Contact= '".$Contact."',
            Location= '".$Location."',
            Election_Area = '".$Election_Area."'
            where CID = ".$CID))
            {die("Error uploading data might cause errors");}
        }
        else
        {
            $loc = upload_pic("images/Candidate/".$CID,"logo",$Clogo);
            if($loc==false){die("Error uploading logo");}

            if(!$this->conn_present->query("Update Candidate 
            set Clogo = '".$loc."',
            Cname= '".$Cname."',
            GPID= ".$GPID.",
            Contact= '".$Contact."',
            Location= '".$Location."',
            Election_Area = '".$Election_Area."'
            where CID = ".$CID))
            {die("Error uploading data might cause errors");}
        }
    }
    function Startelection()
    {
        if(!$this->conn->query("Update Elections set State = 'start' where State = 'prep' ")){die("Error starting elections");}
    }
    function Endelection()
    {
        if(!$this->conn->query("Update Elections set State = 'end' where State = 'start' ")){die("Error starting elections");}
    }

    
}

class Citizen
{
    private $conn;
    private $conn_present;
    private $hash;
    private $year;
    function __construct($conn,$conn_present,$year,$hash)
    {
        $this->conn_present = $conn_present;
        $this->conn = $conn;
        $this->year  = $year;
        $this->hash = $hash;
    }
    function give_vote($VC,$CID,$GPID)
    {
        $data = $this->data_citizen($VC);
        if(!$data){die("Voter doesnt exist");}
        if(!$this->conn->query("Insert into MDVT".$this->year." values('".hash($this->hash,$VC)."','".hash($this->hash,$CID)."','".hash($this->hash,$GPID)."','".hash($this->hash,$data["Wardno"])."','".hash($this->hash,$data["District"])."')"))
        {
            die("Error registering vote");
        };
        $this->conn_present->query("Update Candidate set Vote_count = Vote_count +1 where CID=".$CID);
        $this->conn_present->query("Update Gov_party set Vote_count = Vote_count +1 where GPID=".$GPID);

    }
    function data_citizen($VC)
    {
        if(gettype(intval($VC)) != 'integer'){return null;}
        return $this->conn->query("Select * from Citizen where Voter_card=".$VC)->fetch_assoc();
    }
    function has_voted($VC)
    {
        $D = $this->conn->query("Select * from MDVT".$this->year." where VCMD = '".hash($this->hash,$VC)."'");
        if($D->num_rows>0){return true;}
        return false;
    }
}

class Election
{
    private $conn;
    function __construct($conn)
    {
        $this->conn = $conn;
    }

    function Latest_state(){return $this->conn->query("Select State from Elections where year = (Select max(year) from Elections)")->fetch_assoc()["State"]; }

    function Latest_year(){return $this->conn->query("Select max(year) from Elections")->fetch_assoc()["max(year)"]; }

    function Latest_name(){return "Election".$this->conn->query("Select State from Elections where year = (Select max(year) from Elections)")->fetch_assoc()["State"]; }

    function name_all(){ return $this->conn->query("Select * from Elections"); }

    function Area_all(){return $this->conn->query("Select * from Electionareas"); }

    function Area($ward,$district){return $this->conn->query("Select * from Electionareas where Wardno =".$ward." and District = '".$district."'")->fetch_assoc()["Election_area"]; }
    
}



class Gov_party
{
    private $conn_present;
    private $conn;
    private $year;
    private $hash;
    function __construct($conn,$conn_present,$year,$hash)
    {
        $this->conn_present = $conn_present;
        $this->conn = $conn;
        $this->year = $year;
        $this->hash =$hash;
    }
    function threshold(){ return intval(intval($this->conn->query("Select count(VCMD) from MDVT".$this->year)->fetch_assoc()["count(VCMD)"])/110); }

    function vote_got($GPID)
    {
        $data = $this->conn->query("Select count(GPMD) from MDVT".$this->year." where GPMD = '".hash($this->hash,$GPID)."' group by GPMD");
        if($data->num_rows<=0){return 0;}
        else{return $data->fetch_assoc()["count(GPMD)"];}
    }
    function vote_ward($GPID,$ward,$district)
    {
        return intval($this->conn->query("Select count(GPMD) from MDVT".$this->year."
         where GPMD = '".hash($this->hash,$GPID)."' 
         and WNMD = '".hash($this->hash,$ward)."' 
         and DISMD = '".hash($this->hash,$district)."'
         group by GPMD,WNMD,DISMD")->fetch_assoc()["count(GPMD)"]);
    }
    function verify_vote()
    {
        $GP =  $this->conn_present->query("Select * from Gov_party");

        while($each_party = $GP->fetch_assoc())
        {
            $this->conn_present->query("Update Gov_party set Vote_count = ".$this->vote_got($each_party["GPID"])." where GPID = ".$each_party["GPID"]);
        }
    }
    function With_above_threshold_votes(){ return $this->conn_present->query("Select * from Gov_party where Vote_count >=".$this->threshold()); }

    function All(){ return $this->conn_present->query("Select * from Gov_party");  }

    function ordered_by_vote(){return $this->conn_present->query("Select * from Gov_party order by Vote_count desc");  }

    function AreaOptions($GPID)
    {
        $result = $this->conn_present->query("Select * from candidate where GPID = ".$GPID);
        if($result->num_rows<=0){return $this->conn->query("Select * from Electionareas ");}
        
        $condition = "";
        $count = 1;
        while($row = $result->fetch_assoc())
        {   
            $condition = $condition." Election_area != '".$row["Election_area"]."' ";
            if($count<$result->num_rows){$condition = $condition." and ";}
            $count++;
        }
        return $this->conn->query("Select * from Electionareas where ".$condition);
        
    }
  
}

class Candidate
{
    private $conn_present;
    private $conn;
    private $year;
    private $hash;
    function __construct($conn,$conn_present,$year,$hash)
    {
        $this->conn_present = $conn_present;
        $this->conn = $conn;
        $this->year = $year;
        $this->hash =$hash;
    }
    function vote($CID)
    {
        $data = $this->conn->query("Select count(CMD) from MDVT".$this->year." where CMD = '".hash($this->hash,$CID)."' group by CMD");
        if($data->num_rows<=0){return 0;}
        else{return $data->fetch_assoc()["count(CMD)"];}
    }
    function vote_ward($CID,$ward,$district)
    {
        return intval($this->conn->query("Select count(CMD) from MDVT".$this->year."
         where CMD = '".hash($this->hash,$CID)."' 
         and WNMD = '".hash($this->hash,$ward)."' 
         and DISMD = '".hash($this->hash,$district)."'
         group by CMD,WNMD,DISMD")->fetch_assoc()["count(CMD)"]);
    }
    function verify_vote()
    {
        $Candidate =  $this->conn_present->query("Select * from Candidate");
        while($each_candidate = $Candidate->fetch_assoc())
        {
            $this->conn_present->query("Update Candidate set Vote_count = ".$this->vote($each_candidate["CID"])." where CID = ".$each_candidate["CID"]);
        }
    }
    function all_GP($GPID){return $this->conn_present->query("Select * from Candidate where GPID = ".$GPID);}

    function all() {return $this->conn_present->query("Select * from Candidate"); }
    
    function Top_by_area()
    {
        return $this->conn_present->query("SELECT Cname, Clogo, Election_area, Vote_count
        FROM Candidate
        WHERE (Election_area, Vote_count) IN 
            (SELECT Election_area, MAX(Vote_count) as highest_vote_count 
            FROM Candidate
            GROUP BY Election_area)
        ORDER BY Vote_count desc ");
    }
}

$password = "root";
$user = "root";
$database_location = "localhost";
$hash = "sha512";


$conn = new mysqli($database_location,$user,$password,"ElectionDb");
if($conn->connect_error){die("Error connecting to database");}

$E = new Election($conn);

$Latest_Election_Year = $E->Latest_year();

if($Latest_Election_Year)
{
    $conn_present = new mysqli($database_location,$user,$password,"Election".$Latest_Election_Year);
    if($conn_present->connect_error){die("Error connecting to database");}

    $A = new Admin($conn,$conn_present,$hash);
    $C = new Candidate($conn,$conn_present,$Latest_Election_Year,$hash);
    $P = new Citizen($conn,$conn_present,$Latest_Election_Year,$hash);
    $G = new Gov_party($conn,$conn_present,$Latest_Election_Year,$hash);
}

ini_set('display_errors', 0);

?>
