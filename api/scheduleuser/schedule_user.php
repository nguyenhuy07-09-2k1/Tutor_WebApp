<?php

namespace Ajax;

use Classes\AppUser,
    Classes\TutoringSchedule,
    Classes\SubjectTopic,
    Classes\TeachingTime,
    Classes\DayOfWeek;
use Helpers\Util;
use Library\Session;
require_once(__DIR__ . "../../../vendor/autoload.php");

// $filepath = realpath(dirname(__FILE__));
// include_once $filepath . "../../lib/session.php";

if (!Session::checkRoles(['user', 'tutor'])) {
    header("location:../pages/errors/404");
}
// include_once $filepath . "../../classes/tutoringschedule.php";
// include_once $filepath . "../../classes/appusers.php";
// include_once $filepath . "../../classes/teachingtimes.php";
// include_once $filepath . "../../classes/dayofweeks.php";
// include "../classes/subjecttopics.php";

// include_once $filepath . "../../helpers/utilities.php";
// include_once $filepath . "../../helpers/format.php";

$_tutoring_schedule = new TutoringSchedule();
$_user = new AppUser();
$_subjecttopic = new SubjectTopic();
$_teaching_time = new TeachingTime();
$_day_of_week = new DayOfWeek();


if ($_SERVER["REQUEST_METHOD"] === "POST") :
    if (isset($_POST) && !empty($_POST)) :
        $get_tutoring_schedule = $_tutoring_schedule->getTutoringScheduleByUserId(1, Session::get("userId"), $_POST);

        if ($get_tutoring_schedule->data->num_rows > 0) :

?>

            <div class="card">
                <div class="card-body py-md-4 px-md-4 ">
                    <div class="accordion" id="accordion-tutoring-schedule">

                        <?php

                        while ($tutoring_schedule = $get_tutoring_schedule->data->fetch_assoc()) :
                            $user = $_user->getInfoByUserId($tutoring_schedule["userId"])->fetch_assoc();
                        ?>


                            <div class="accordion-item border-0">
                                <div class="accordion-header" id="heading-<?= $user["username"]; ?>">
                                    <button class="accordion-button bg-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $user["username"]; ?>" aria-expanded="true" aria-controls="collapseOne">
                                        <?php

                                        if ($user !== false) :
                                        ?>

                                            <div class="d-flex align-items-start">
                                                <img src="<?= Util::getCurrentURL(2) . "public/"  . $user["imagepath"]; ?>" class="rounded-circle avatar-sm img-thumbnail" alt="profile-image" onclick="ShowImg(this.src);">
                                                <div class="w-100 ms-3 align-self-end">
                                                    <h6 class="my-1 fw-bold" style="color: #333333 !important;"><?= $user["lastname"] . ' ' . $user["firstname"]; ?></h6>
                                                    <p class="text-muted">@id: <?= $user["username"]; ?></p>

                                                </div>
                                            </div>


                                    </button>
                                </div>
                                <div id="collapse<?= $user["username"]; ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?= $user["username"]; ?>" data-bs-parent="#accordion-tutoring-schedule">
                                    <div class="accordion-body">

                                        <div class="table-responsive">
                                            <table class="table table-striped table-schedule-user">
                                                <thead class="table-cerulean">
                                                    <tr>
                                                        <th scope="col" class="d-none">ID</th>
                                                        <th scope="col">Thứ</th>
                                                        <th scope="col">Thời gian</th>
                                                        <th scope="col">Môn học</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $get_schedule = $_tutoring_schedule->GetTutoringSchedule_User($tutoring_schedule["tutorId"], Session::get("userId"), 1, $_POST);
                                                    if ($get_schedule) :
                                                        while ($schedule = $get_schedule->fetch_assoc()) :

                                                    ?>
                                                            <tr class="container-schedule">

                                                                <th scope="row" class="text-start th-id d-none" data-value="<?= $schedule["id"] ?>"><?= $schedule["id"] ?></th>
                                                                <td scope="row" class="text-start td-day" data-value="<?= $schedule["dayofweekId"] ?>"><?= $schedule["day"] ?></td>
                                                                <td scope="row" class="text-start td-time" data-value="<?= $schedule["timeId"] ?>"><?= $schedule["time"] ?></td>
                                                                <td scope="row" class="text-start td-topic-name" data-value="<?= $schedule["subject_topicId"] ?>"><?= $schedule["topicName"] ?></td>



                                                            </tr>



                                                    <?php endwhile;
                                                    endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            <?php endif; ?>
                            </div>
                        <?php

                        endwhile;

                        ?>
                    </div>
                </div>
            </div>


        <?php

            echo ' <nav aria-label="Page navigation example " id="pagination-nav" class="mt-3">';
            echo $_tutoring_schedule->getPaginatorUserSchedule($_POST);
            echo '</div>';
        else :
        ?>

            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                Không có lịch học vào hôm nay.
            </div>

<?php

        endif;
    endif;
endif;
