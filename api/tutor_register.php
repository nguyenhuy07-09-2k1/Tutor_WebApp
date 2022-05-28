<?php

namespace Ajax;


use Classes\Tutor, Classes\TeachingTime, Classes\TeachingSubject;
use Helpers\Format;
use Library\Session;

$filepath = realpath(dirname(__FILE__));

include_once $filepath . "../../lib/session.php";
if (Session::checkRoles(['tutor'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["author" => "isTutor"]);
    exit();
}
include_once $filepath . "../../classes/tutors.php";
include_once $filepath . "../../classes/teachingtimes.php";
include_once $filepath . "../../classes/teachingsubjects.php";

include_once $filepath . "../../helpers/format.php";

$_tutor = new Tutor();
$_teaching_time = new TeachingTime();
$_teaching_subject = new TeachingSubject();
if ($_SERVER["REQUEST_METHOD"] === "POST" ) {

    if (!isset($_POST["token"]) || !isset($_SESSION["csrf_token"])) { exit(); }

    if (
        (isset($_POST["currentPhone"]) && !empty($_POST["currentPhone"]))
        && (isset($_POST["currentEmail"]) && !empty($_POST["currentEmail"]))
        && (isset($_POST["currentAddress"]) && !empty($_POST["currentAddress"]))
        && (isset($_POST["currentJob"]) && !empty($_POST["currentJob"]))
        && (isset($_POST["currentProvince"]) && !empty($_POST["currentProvince"]))
        && (isset($_POST["currentCollage"]) && !empty($_POST["currentCollage"]))
        && (isset($_POST["graduateYear"]) && is_numeric($_POST["graduateYear"]))
        && (isset($_POST["districts"]) && !empty($_POST["districts"]))
        && (isset($_POST["teachingForm"]) && !empty($_POST["teachingForm"]))
        && (isset($_POST["description"]) && !empty($_POST["description"]))
        && hash_equals($_POST["token"], $_SESSION["csrf_token"] )
    ) {

        $currentPhone = Format::validation($_POST["currentPhone"]);
        $currentEmail = Format::validation($_POST["currentEmail"]);
        $currentAddress = Format::validation($_POST["currentAddress"]);
        $currentJob = Format::validation($_POST["currentJob"]);
        $currentProvince = Format::validation($_POST["currentProvince"]);
        $currentCollage = Format::validation($_POST["currentCollage"]);
        $graduateYear = Format::validation($_POST["graduateYear"]);
        $districts = Format::validation($_POST["districts"]);
        $teachingForm = Format::validation($_POST["teachingForm"]);
        $description = Format::validation($_POST["description"]);
        $linkFace = isset($_POST["linkFace"]) && !empty($_POST["linkFace"]) ? Format::validation($_POST["linkFace"]) : NULL;
        $linkTwit = isset($_POST["linkTwit"]) && !empty($_POST["linkTwit"]) ? Format::validation($_POST["linkTwit"]) : NULL;
        $data = array(Session::get("userId"), $description, $currentPhone, $currentEmail, $currentAddress, $currentCollage, $graduateYear,  $currentJob, $currentProvince, $teachingForm,  $districts, $linkFace, $linkTwit);

        // print_r($teachingForm . "teach");
        $numTutor = $_tutor->countTutorByUserId(Session::get("userId"))->fetch_assoc()["countTutor"];

        if ($numTutor === 0) {
            // $insert_register_tutor = $_tutor->addRegisterTutor($data);
            $insert_register_tutor = false;

            if ($insert_register_tutor) {
                $tutorId = $_tutor->getTutorIdByUserId(Session::get("userId"))->fetch_assoc()["tutorId"];

                if (isset($_POST["subjects"]) && !empty($_POST["subjects"])) {
                    foreach ($_POST["subjects"] as $key => $topic) {

                        $topicId = explode("-", $topic["id"])[1];
                        if (Format::validation($topicId)) {
                            $_teaching_subject->insertTeachingSubjects($tutorId, $topicId);
                            // print_r($topicId . " ");
                        }
                    }
                }

                if (isset($_POST["Monday"]) && $_POST["Monday"] !== "false" && is_array($_POST["Monday"])) {
                    foreach ($_POST["Monday"]["timeId"] as $timeId) {
                        // print_r($timeId);
                        $_teaching_time->insertTeachingTime($tutorId, $_POST["Monday"]["dayId"], $timeId);
                    }
                }


                if (isset($_POST["Tuesday"]) && $_POST["Tuesday"] !== "false" && is_array($_POST["Tuesday"])) {
                    foreach ($_POST["Tuesday"]["timeId"] as $timeId) {
                        // print_r($timeId);
                        $_teaching_time->insertTeachingTime($tutorId, $_POST["Tuesday"]["dayId"], $timeId);
                    }
                }

                if (isset($_POST["Wednesday"]) && $_POST["Wednesday"] !== "false" && is_array($_POST["Wednesday"])) {
                    foreach ($_POST["Wednesday"]["timeId"] as $timeId) {
                        // print_r( gettype($_POST["Wednesday"]));
                        $_teaching_time->insertTeachingTime($tutorId, $_POST["Wednesday"]["dayId"], $timeId);
                    }
                }

                if (isset($_POST["Thursday"]) && $_POST["Thursday"] !== "false" && is_array($_POST["Thursday"])) {
                    foreach ($_POST["Thursday"]["timeId"] as $timeId) {
                        // print_r($timeId);
                        $_teaching_time->insertTeachingTime($tutorId, $_POST["Thursday"]["dayId"], $timeId);
                    }
                }

                if (isset($_POST["Friday"]) && $_POST["Friday"] !== "false" && is_array($_POST["Friday"])) {
                    foreach ($_POST["Friday"]["timeId"] as $timeId) {
                        // print_r($timeId);
                        $_teaching_time->insertTeachingTime($tutorId, $_POST["Friday"]["dayId"], $timeId);
                    }
                }

                if (isset($_POST["Saturday"]) && $_POST["Saturday"] !== "false" && is_array($_POST["Saturday"])) {
                    foreach ($_POST["Saturday"]["timeId"] as $timeId) {
                        // print_r($timeId);
                        $_teaching_time->insertTeachingTime($tutorId, $_POST["Saturday"]["dayId"], $timeId);
                    }
                }

                if (isset($_POST["Sunday"]) && $_POST["Sunday"] !== "false" && is_array($_POST["Sunday"])) {
                    foreach ($_POST["Sunday"]["timeId"] as $timeId) {
                        // print_r($timeId);
                        $_teaching_time->insertTeachingTime($tutorId, $_POST["Sunday"]["dayId"], $timeId);
                    }
                }
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["insert" => "successful"]);
            }
            else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(["insert" => "fail"]);
        } 
        }else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["author" => "isTutor"]);
        }
    }
}else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["token" => "not_match"]);
}