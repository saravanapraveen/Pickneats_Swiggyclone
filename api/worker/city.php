<?php
    class city{
        public $city_name,$city_latitude,$city_longitude,$conn;

        function __construct($conn) {
            $this->conn = $conn;
        }

        function getAllCity(){
            $i = 0;
            
            $returner = array();

            $sql = "SELECT * FROM city ORDER BY city_name ASC";
            $result = $this->conn->query($sql);
            while($row = $result->fetch_assoc()){
                $returner[$i]['city_id'] = $row['city_id'];
                $returner[$i]['city_name'] = $row['city_name'];

                $i++;
            }

            return $returner;
        }

        function getCity($city_id){
            $sql = "SELECT * FROM city WHERE city_id='$city_id'";
            $result = $this->conn->query($sql);
            $row = $result->fetch_assoc();

            $this->city_name = $row['city_name'];
            $this->city_latitude = $row['city_latitude'];
            $this->city_longitude = $row['city_longitude'];

            return $this;
        }
    }
?>