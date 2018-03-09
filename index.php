<!doctype html>
<link href="./style.css" rel="stylesheet" type="text/css" />

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="simpel distributed retrospective">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Allan Nørgaard Skov" />

    <title>Simple Distributed Retrospective</title>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "retro";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    ?>

</head>
<body>
<h1 style="font-family:Cursive;letter-spacing:4px;color:#000000;">Distributed Retrospective</h1>


<?php


//strip funny chars:
if (isset($_POST["subject"])) $_POST["subject"] = str_replace("'", "\"", htmlspecialchars($_POST["subject"]));
if (isset($_POST["newRetrospective"]))$_POST["newRetrospective"] = str_replace("'", "\"", htmlspecialchars($_POST["newRetrospective"]));
if (isset($_POST["newUsername"]))$_POST["newUsername"] = str_replace("'", "\"", htmlspecialchars($_POST["newUsername"]));
if (isset($_POST["groupNote"]))$_POST["groupNote"] = str_replace("'", "\"", htmlspecialchars($_POST["groupNote"]));



//add subjects:
if (isset($_POST['yourName']) && isset($_POST['subject'])) {
    if (strlen($_POST['yourName']) > 1 && strlen($_POST['subject']) > 2) {
        $sql =
            "INSERT INTO subjects (retrospective, name, category, subject)
             VALUES ('" . $_POST["retrospective"] . "','" . $_POST["yourName"] . "','" . $_POST["category"] . "','" . $_POST["subject"] .
            "')";
        $result = mysqli_query($conn, $sql);
    }
}


if (isset($_POST['SubmitNewRetrospective']) && strlen($_POST['newRetrospective']) > 2) {
    $sql =
        "INSERT INTO subjects (retrospective, name, category, subject)
             VALUES ('" . $_POST["newRetrospective"] . "','" . $_POST["yourName"] . "','" . $_POST["category"] . "','" . $_POST["subject"] .
        "')";
    $result = mysqli_query($conn, $sql);
    $_POST["retrospective"] = $_POST['newRetrospective'];
}


if (isset($_POST['SubmitNewUsername']) && strlen($_POST['newUsername']) > 2) {
    $sql =
        "INSERT INTO subjects (retrospective, name, category, subject)
             VALUES ('" . $_POST["retrospective"] . "','" . $_POST["newUsername"] . "','" . $_POST["category"] . "','" . $_POST["subject"] .
        "')";
    $result = mysqli_query($conn, $sql);
    $_POST["yourName"] = $_POST['newUsername'];
}


//update votes:
if (isset($_POST['submitVotes'])) {

    //start by deleteing all votes for this user in this retrospective only
    $sql = "delete from votes 
                where subjectId IN (SELECT id FROM subjects WHERE retrospective = '" . $_POST["retrospective"] . "') 
                AND name = '" . $_POST["yourName"] . "'";
    $result = mysqli_query($conn, $sql);

    if (isset($_POST["voteCheckbox"])) {
        foreach ($_POST["voteCheckbox"] as &$value) {
            $sql = "INSERT INTO votes (name, subjectId) VALUES ('" . $_POST["yourName"] . "','" . $value . "')";
            $result = mysqli_query($conn, $sql);
        }
    }
}



//Group items:
if (isset($_POST['groupItems'])) {

    if (isset($_POST["voteCheckbox"])) {
        foreach ($_POST["voteCheckbox"] as &$value) {
            $sql = "update subjects set category = '" . $_POST["groupItemsCategory"] . "',  subject = CONCAT('(GROUP: " . $_POST["groupNote"] . ") ', subject) where id = " . $value;
            $result = mysqli_query($conn, $sql);
        }
    }
}




?>

<form action="index.php" method="post">


    <table border="0">
        <tr>
            <td>
                Select a retrospective:
            </td>
            <td>
                <select name="retrospective" style="width:200px;" onchange='this.form.submit()'>
                    <?php
                    $retrospectiveResult = $conn->query("SELECT distinct retrospective FROM subjects order by datetime desc");
                    while ($row = $retrospectiveResult->fetch_assoc()) {

                        if (!isset($_POST["retrospective"])) {
                            $_POST["retrospective"] = $row["retrospective"];
                        }

                        $selected = "";
                        if ($row["retrospective"] == $_POST["retrospective"]) {
                            $selected = "selected";
                        }
                        echo "<option value='" . $row["retrospective"] . "' $selected>" . $row["retrospective"] . "</option>";
                    }
                    ?>
                </select>
                or create a new: <input type="text" name="newRetrospective"> <input type="submit" value="submit"
                                                                                    name="SubmitNewRetrospective">
            </td>
        </tr>

        <tr>
            <td>
                Select your username:
            </td>
            <td>
                <select name="yourName" style="width:200px;" onchange='this.form.submit()'>
                    <?php
                    $retrospectiveResult = $conn->query("SELECT distinct name FROM subjects where name != '' order by name asc");
                    while ($row = $retrospectiveResult->fetch_assoc()) {

                        if (!isset($_POST["yourName"])) {
                            $_POST["yourName"] = $row["name"];
                        }

                        $selected = "";
                        if ($row["name"] == $_POST["yourName"]) {
                            $selected = "selected";
                        }
                        echo "<option value='" . $row["name"] . "' $selected>" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
                or create a new: <input type="text" name="newUsername"> <input type="submit" value="submit"
                                                                               name="SubmitNewUsername">
            </td>
        </tr>


        <tr>
            <td colspan="3">
                <br>
            </td>
        </tr>


        <tr>
            <td>
                Choose a catagory:
            </td>
            <td>
                <select name="category" style="width:200px;">
                    <option value="No category"></option>
                    <option value="Start doing">Start doing</option>
                    <option value="Stop doing">Stop doing</option>
                    <option value="Keep doing">Keep doing</option>
                    <option value="Do more of">Do more of</option>
                    <option value="Do less of">Do less of</option>
                </select>
            </td>
        </tr>


        <tr>
            <td>
                Write your subject:
            </td>
            <td>
                <textarea rows="4" cols="74" name="subject"></textarea>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type="submit" value="Submit subject / refresh screen">
            </td>
            <td></td>
        </tr>


    </table>
    <br>

    <hr>

    In this retrospective (<b><?= $_POST["retrospective"] ?></b>), the following users have contributed:

    <?php
    $sql = "SELECT distinct(name) FROM subjects where retrospective = '" . $_POST["retrospective"] . "' order by name";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {
        echo "<b>" . $row["name"] . "</b>, ";
    }
    ?>


    <br>
    It was created
    <?php
    $sql = "select MIN(datetime), MAX(datetime) from subjects where retrospective = '" . $_POST["retrospective"] . "' order by name";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {
        echo "<b>" . $row["MIN(datetime)"] . "</b>";
        echo ", and last contribution was made";
        echo "<b> " . $row["MAX(datetime)"] . "</b>";
    }
    ?>


    <br>
    <br>

    <?php

    //first search all categories:
    $categoryResult =
        $conn->query("SELECT distinct category FROM subjects WHERE retrospective = '" . $_POST["retrospective"] .
            "' AND subject != '' order by datetime");
    while ($row = $categoryResult->fetch_assoc()) {
        echo "<h3>" . $row["category"] . "</h3>";

        //then for each category, execute the actual search query:
//      $result = $conn->query("SELECT * FROM subjects WHERE retrospective = '" . $_POST["retrospective"] . "' AND category = '" . $row["category"] . "'");
        $result =
            $conn->query("SELECT id, subject, subjects.name, subjects.datetime, count(subjectId) FROM subjects LEFT JOIN votes ON subjects.id = votes.subjectId WHERE retrospective = '" .
                $_POST["retrospective"] . "' AND category = '" . $row["category"] .
                "' AND subject != '' group by subject order by count(subjectId) desc");
        while ($row = $result->fetch_assoc()) {

            $personsVoted = "";
            $voteResult = $conn->query("SELECT name FROM `votes` WHERE subjectId = " . $row["id"]);
            while ($rowPersonsVoted = $voteResult->fetch_assoc()) {
                $personsVoted = $personsVoted . $rowPersonsVoted["name"] . ", ";
            }

            echo "
                <div title='$personsVoted voted on this subject. Subject created: " . $row["datetime"] . " with id=" . $row["id"] . "'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                . $row["count(subjectId)"]
                . "&nbsp;<input type='checkbox' name='voteCheckbox[]' value='" . $row["id"] . "'>&nbsp;"
                . $row["subject"]
                . " <i>(" . $row["name"] . "</i>)"
                . "</div>";
        }
    }


    ?>

    <br><br>
    <table>
        <tr>
            <td>
                Either
            </td>
            <td>
                <input type="submit" value="Votes on the selected items" name="submitVotes" style="width:250px">
            </td>
        </tr>
        <tr>
            <td>
                or
            </td>
            <td>
                <input type="submit" value="Group the selected items" name="groupItems" style="width:250px"> into this category
                <select name="groupItemsCategory" style="width:200px;">
                    <option value="No category"></option>
                    <option value="Start doing">Start doing</option>
                    <option value="Stop doing">Stop doing</option>
                    <option value="Keep doing">Keep doing</option>
                    <option value="Do more of">Do more of</option>
                    <option value="Do less of">Do less of</option>
                </select>
                with this group-note <input type="text" name="groupNote">
            </td>
        </tr>
    </table>




</form>

<?php
$conn->close();
?>


<div style="position: fixed; left: 0; bottom: 0; width: 100%; text-align: right;">
<!--    <hr>-->
    - less is more -
</div>
</body>
</html>


