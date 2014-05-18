<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        #stat_table
        {
            width: 600px;
            border: 1px solid;
            background-color: #F4D9D9;
        }
        #stat_table td
        {
            border: 1px solid #90A0A0;
            text-align: center;
        }
    </style>
</head>
<body>
    <table id="stat_table">
        <tr>
            <td>&nbsp;</td>
            <td><b>English</b></td>
            <td><b>Romanian</b></td>
        </tr>
        <tr>
            <td><b>Total Body Size</b></td>
            <td>{total_text_en} characters</td>
            <td>{total_text_ro} characters</td>
        </tr>
        <tr>
            <td><b>Minimum Body Size</b></td>
            <td>{min_text_en} characters</td>
            <td>{min_text_ro} characters</td>
        </tr>
        <tr>
            <td><b>Maximum Body Size</b></td>
            <td>{max_text_en} characters</td>
            <td>{max_text_ro} characters</td>
        </tr>
        <tr>
            <td><b>Average Body Size</b></td>
            <td>{avg_text_en} characters</td>
            <td>{avg_text_ro} characters</td>
        </tr>
        <tr>
            <td><b>Total Title Size</b></td>
            <td>{total_title_en} characters</td>
            <td>{total_title_ro} characters</td>
        </tr>
        <tr>
            <td><b>Minimum Title Size</b></td>
            <td>{min_title_en} characters</td>
            <td>{min_title_ro} characters</td>
        </tr>
        <tr>
            <td><b>Maximum Title Size</b></td>
            <td>{max_title_en} characters</td>
            <td>{max_title_ro} characters</td>
        </tr>
        <tr>
            <td><b>Average Title Size</b></td>
            <td>{avg_title_en} characters</td>
            <td>{avg_title_ro} characters</td>
        </tr>
    </table>
    <br />
    <i>The above data is computed for {nr_problems} English problems and {nr_problems_translated} Romanian problems.</i>
</body>
</html>