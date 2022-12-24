<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF template</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400&display=swap');
    </style>
</head>
<body style="width: 100vw">
<div align="center" style="width: 100%">
    <img
        src="https://znoxrd.stripocdn.email/content/guids/CABINET_bd0eb926e176d9bdb3312655781e95f7/images/copia_de_logo_mapp_game_bkg_blueremovebgpreview_c8d.png"
        alt="MappGame"/>
    <h2 style="font-family: 'Kanit', sans-serif;">Patient: {{$resp['patient']->name}}</h2>
    <h2 style="font-family: 'Kanit', sans-serif;">Number of plays: {{$resp['count_plays']}}</h2>
    <h2 style="font-family: 'Kanit', sans-serif;">Requested by: {{$resp['professional_name']}}</h2>
    <h2 style="font-family: 'Kanit', sans-serif;">Generated On: {{$resp['current_date']}}</h2>
    @if(!empty($resp['answers']))
    <table align="center" style="width: 50%">
        <thead>
        <tr>
            <th scope="col"
                style="min-width:20px;font-family: 'Kanit', sans-serif;background-color: #3869d4;color:#fff;text-align: center;border: 1px solid #000;border-collapse: collapse;">
                ID
            </th>
            <th scope="col"
                style="font-family: 'Kanit', sans-serif;background-color: #3869d4;color:#fff;text-align: center;border: 1px solid #000;border-collapse: collapse;">
                HITS
            </th>
            <th scope="col"
                style="font-family: 'Kanit', sans-serif;background-color: #3869d4;color:#fff;text-align: center;border: 1px solid #000;border-collapse: collapse;">
                ERRORS
            </th>
            <th scope="col"
                style="font-family: 'Kanit', sans-serif;background-color: #3869d4;color:#fff;text-align: center;border: 1px solid #000;border-collapse: collapse;">
                DATE
            </th>
        </tr>
        </thead>
        <tbody>
        {{$i = 1}}
        @foreach($resp['answers'] as $answer)
            <tr>
                <th style="font-family: 'Kanit', sans-serif;text-align: center;border: 1px solid #000;border-collapse: collapse;">{{ $i }}</th>
                <td style="font-family: 'Kanit', sans-serif;
                text-align: center;
                border: 1px solid #000;
                border-collapse: collapse;
                {{$answer->hits === 5 ? 'background: #00b66c;color:#fff;' : ''}}">{{ $answer->hits ?? 0 }}</td>
                <td style="font-family: 'Kanit', sans-serif;
                text-align: center;
                border: 1px solid #000;
                border-collapse: collapse;
                {{$answer->errors === 5 ? 'background: #ff5454;color:#fff;' : ''}}">{{ $answer->errors ?? 0 }}</td>
                <td style="font-family: 'Kanit', sans-serif;text-align: center;border: 1px solid #000;border-collapse: collapse;">{{ $answer->created_at }}</td>
            </tr>
            {{$i++}}
        @endforeach
        </tbody>
    </table>
    @endif
    <span style="margin-top: 20px">©Mapp Games {{date('Y')}} all Rights Reserved.</span>
</div>
</body>
</html>
