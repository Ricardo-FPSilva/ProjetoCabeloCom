<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seção em Desenvolvimento</title>
    <style>
        body {
            min-height: 100vh;
            background: #ffffff;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
        }

        h1 {
            margin-top: 20px;
            font-size: 28px;
            color: #854775;
        }

        p {
            font-size: 18px;
            color: #555;
            max-width: 80%;
        }

        .box {
            position: relative;
            transform-style: preserve-3d;
            width: 80px;
            height: 120px;
            transform: perspective(1000px) rotateY(-45deg);
        }

        .box::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: -110px;
            width: 90px;
            height: 150px;
            background: #85477522;
            transform: rotateX(90deg);
            filter: blur(30px);
            opacity: 0.3;
        }

        .box div {
            position: absolute;
            inset: 0;
            transform-style: preserve-3d;
            animation: animate 5s linear infinite;
        }

        .box div span {
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, #a29bfe, #6C5CE7); /* novo degradê roxo */
    border-radius: 20px;
    transform: rotateX(calc(var(--i) * 45deg));
    transform-style: preserve-3d;
}


        @keyframes animate {
            0% {
                transform: perspective(1000px) rotateX(0deg);
            }
            100% {
                transform: perspective(1000px) rotateX(359deg);
            }
        }

        a {
            margin-top: 20px;
            color: #854775;
            text-decoration: underline;
        }

        a:hover {
            color: #a15a9b;
        }
    </style>
</head>
<body>
    <div class="box">
        <div>
            <span style="--i:1;"></span>
            <span style="--i:2;"></span>
            <span style="--i:3;"></span>
            <span style="--i:4;"></span>
        </div>
    </div>

    <h1>Seção em Desenvolvimento</h1>
    <p>Esta funcionalidade ainda está sendo construída.<br>Em breve estará disponível no sistema.</p>
    <a href="javascript:history.back()">← Voltar</a>
</body>
</html>
