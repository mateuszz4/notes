<?php

$plik = "notes.txt";
$wiadomosc = "";
$kolor = "green";

// dodawanie notatki
if(isset($_POST['akcja']) && $_POST['akcja'] == "dodaj")
{
    $tresc = $_POST['notatka'];

    if($tresc == "")
    {
        $wiadomosc = "Wpisz tresc notatki!";
        $kolor = "red";
    }
    else
    {
        $data = date("Y-m-d H:i:s");
        $linia = "[".$data."] ".$tresc."\n";

        $fp = fopen($plik, "a") or die("Blad: nie mozna otworzyc pliku!");
        fwrite($fp, $linia);
        fclose($fp);

        $wiadomosc = "Notatka zostala zapisana!";
        $kolor = "green";
    }
}

// usuwanie notatek
if(isset($_POST['akcja']) && $_POST['akcja'] == "usun")
{
    if(file_exists($plik))
    {
        unlink($plik);
        $wiadomosc = "Wszystkie notatki zostaly usuniete.";
        $kolor = "green";
    }
    else
    {
        $wiadomosc = "Nie ma zadnych notatek do usuniecia.";
        $kolor = "red";
    }
}

// wczytanie notatek
$notatki = array();
if(file_exists($plik))
{
    $notatki = file($plik, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>System Notatek</title>
    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
            font-size: 15px;
            color: #222;
        }

        .container {
            width: 620px;
            margin: 40px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 30px;
        }

        h1 {
            font-size: 22px;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        p.opis {
            color: #666;
            font-size: 13px;
            margin-bottom: 20px;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        .wiadomosc {
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .wiadomosc.ok  { background-color: #d4edda; color: #2d6a3f; border: 1px solid #b8dac2; }
        .wiadomosc.err { background-color: #f8d7da; color: #7a1f25; border: 1px solid #f0b8bc; }

        h2 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        textarea {
            width: 100%;
            height: 80px;
            padding: 8px 10px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        textarea:focus {
            border-color: #2563eb;
            outline: none;
        }

        .btn-blue {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 8px 18px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 8px;
        }

        .btn-blue:hover { background-color: #1d4ed8; }

        .btn-red {
            background-color: white;
            color: #c0392b;
            border: 1px solid #c0392b;
            padding: 8px 18px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-red:hover { background-color: #fdecea; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }

        th {
            background-color: #f0f4ff;
            padding: 7px 10px;
            text-align: left;
            border-bottom: 1px solid #dde3f0;
            color: #444;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f9fbff; }

        .nr    { color: #2563eb; font-weight: bold; width: 40px; }
        .data  { color: #888; font-size: 13px; width: 160px; }

        footer {
            text-align: center;
            color: #aaa;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>System Notatek</h1>
    <p class="opis">Notatki sa zapisywane w pliku <strong>notes.txt</strong></p>

    <hr>

    <?php if($wiadomosc != ""): ?>
        <?php if($kolor == "green"): ?>
            <div class="wiadomosc ok"><?php echo $wiadomosc; ?></div>
        <?php else: ?>
            <div class="wiadomosc err"><?php echo $wiadomosc; ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Dodawanie notatki -->
    <h2>Dodaj nowa notatke</h2>
    <form method="post" action="">
        <input type="hidden" name="akcja" value="dodaj">
        <textarea name="notatka" placeholder="Wpisz tresc notatki..."></textarea>
        <br>
        <button type="submit" class="btn-blue">Zapisz notatke</button>
    </form>

    <hr>

    <!-- Lista notatek -->
    <h2>Wszystkie notatki (<?php echo count($notatki); ?>)</h2>

    <?php if(count($notatki) == 0): ?>

        <p style="color: #888;">Brak notatek. Dodaj pierwsza powyzej.</p>

    <?php else: ?>

        <table>
            <tr>
                <th class="nr">Nr</th>
                <th class="data">Data i godzina</th>
                <th>Tresc</th>
            </tr>
            <?php
            $nr = 1;
            foreach($notatki as $linia)
            {
                preg_match('/^\[(.+?)\]\s*(.*)$/', $linia, $m);
                $data  = $m[1];
                $tresc = $m[2];

                echo "<tr>";
                echo "<td class='nr'>".$nr."</td>";
                echo "<td class='data'>".$data."</td>";
                echo "<td>".$tresc."</td>";
                echo "</tr>";

                $nr++;
            }
            ?>
        </table>

        <form method="post" action="">
            <input type="hidden" name="akcja" value="usun">
            <button type="submit" class="btn-red" onclick="return confirm('Czy na pewno chcesz usunac wszystkie notatki?')">
                Usun wszystkie notatki
            </button>
        </form>

    <?php endif; ?>

    <hr>
    <footer></footer>

</div>

</body>
</html>
