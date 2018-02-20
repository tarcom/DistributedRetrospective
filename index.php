<!doctype html>
<!--
    todo:
    ajax
    url encode all inputs (mostly subject)


-->


<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="simpel distributed retrospective">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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


?>

<form action="index.php" method="post">


    <table border="0">
        <tr>
            <td>
                Select retrospective:
            </td>
            <td>
                <select name="retrospective" style="width:200px;">
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
                <select name="yourName" style="width:200px;">
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

    </table>
    <input type="submit" value="Submit subject / refresh screen">


    <hr>

    <?php

    //first search all categories:
    $categoryResult =
        $conn->query("SELECT distinct category FROM subjects WHERE retrospective = '" . $_POST["retrospective"] . "' AND subject != '' order by datetime");
    while ($row = $categoryResult->fetch_assoc()) {
        echo "<h3>" . $row["category"] . "</h3>";

        //then for each category, execute the actual search query:
//      $result = $conn->query("SELECT * FROM subjects WHERE retrospective = '" . $_POST["retrospective"] . "' AND category = '" . $row["category"] . "'");
        $result = $conn->query("SELECT id, subject, subjects.name, subjects.datetime, count(subjectId) FROM subjects LEFT JOIN votes ON subjects.id = votes.subjectId WHERE retrospective = '" . $_POST["retrospective"] . "' AND category = '" . $row["category"] . "' AND subject != '' group by subject order by count(subjectId) desc");
        while ($row = $result->fetch_assoc()) {
            echo "
                <div  title='" . $row["name"] . " - " . $row["datetime"] . "'>"
                . $row["count(subjectId)"]
                . "<input type='checkbox' name='voteCheckbox[]' value='" . $row["id"] . "'>"
                . $row["subject"]
                . "</div>";
        }
    }


    ?>

    <br><br>
    <input type="submit" value="Submit votes" name="submitVotes">


</form>

<?php
$conn->close();
?>


<div style="position: fixed; left: 0; bottom: 0; width: 100%; text-align: center;">
    <hr>
    less is more - alsk@nykredit.dk
</div>
</body>
</html>


