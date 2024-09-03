<?php include('layouts/header.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Signo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .result-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .result-header {
            margin-bottom: 20px;
            text-align: center;
        }
        .result-header h2 {
            color: #007bff;
        }
        .result-description {
            font-size: 1.2rem;
            color: #555;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-container">
            <?php
            // Recebe a data de nascimento
            $data_nascimento = $_POST['data_nascimento'];

            // Verifica se a data foi enviada
            if (isset($data_nascimento)) {
                // Carrega o XML com os signos
                $signos = simplexml_load_file("signos.xml");

                // Converte a data de nascimento para o formato 'd/m'
                $dataNascimento = DateTime::createFromFormat('Y-m-d', $data_nascimento);
                $dataNascimentoFormatada = $dataNascimento->format('d/m');

                $signoEncontrado = false;
                foreach ($signos->signo as $signo) {
                    $dataInicio = DateTime::createFromFormat('d/m', (string)$signo->dataInicio);
                    $dataFim = DateTime::createFromFormat('d/m', (string)$signo->dataFim);

                    // Ajustar para o mesmo ano
                    $dataInicio->setDate($dataNascimento->format('Y'), $dataInicio->format('m'), $dataInicio->format('d'));
                    $dataFim->setDate($dataNascimento->format('Y'), $dataFim->format('m'), $dataFim->format('d'));

                    // Verificar se dataFim é menor que dataInicio (intervalo atravessando o ano)
                    if ($dataFim < $dataInicio) {
                        $dataFim->modify('+1 year');
                    }

                    // Verifica se a data de nascimento está no intervalo
                    if ($dataNascimento >= $dataInicio && $dataNascimento <= $dataFim) {
                        echo "<div class='result-header'>";
                        echo "<h2>Seu Signo é: " . $signo->signoNome . "</h2>";
                        echo "</div>";
                        echo "<div class='result-description'>";
                        echo "<p>" . $signo->descricao . "</p>";
                        echo "</div>";
                        $signoEncontrado = true;
                        break;
                    }
                }

                if (!$signoEncontrado) {
                    echo "<p>Signo não encontrado. Verifique a data fornecida.</p>";
                }
            } else {
                echo "<p>Por favor, insira sua data de nascimento.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
