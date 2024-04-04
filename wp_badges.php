<?php 
$pagina = $_GET['pagina'];

// URL que contém o JSON
$url = 'https://moodlepos.nepuga.edu.br/badges/allbadges.php?page='.$pagina.'&format=json';

// Obtendo o JSON da URL
$json = file_get_contents($url);

// Decodificando o JSON para um array associativo
$data = json_decode($json, true);

// Verificando se houve algum erro na decodificação do JSON
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    die('Erro ao decodificar o JSON: ' . json_last_error_msg());
}
$html = '
<style>
    .pagination-box {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    .pagination-box a {
        display: inline-block;
        padding: 8px 16px;
        text-decoration: none;
        color: black;
        border: 1px solid #ddd;
        margin: 0 2px;
    }

    .pagination-box a:hover {
        background-color: #ddd;
    }

    .pagination-box a.active {
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
    }
</style>

<table style="color:black">
  <thead>
    <tr>
      <th scope="col">Imagem</th>
      <th scope="col">Nome</th>
      <th scope="col">Descrição</th>
    </tr>
  </thead>
  <tbody>
';

// Exibindo os dados
foreach ($data as $badge) {
    //echo "ID: " . $badge['id'] . "\n";
    //echo "Nome: " . $badge['name'] . "\n";
    //echo "Descrição: " . $badge['description'] . "\n";
    // Exiba outras informações conforme necessário
    //echo "\n";
    $html .="
    <tr>
    <td><img style='width:150px;' src='https://moodlepos.nepuga.edu.br/pluginfile.php/1//badges/badgeimage/".$badge['id']."/f3'></td>
    <td>".$badge['name']."</td>
    <td>".$badge['description']."</td>
    </tr>
    ";
}

$html .= '
    </tbody>
</table>
';

// Links de paginação manual
$links_paginacao = '';
for ($i = 1; $i <= 9; $i++) {
    $links_paginacao .= "<a href='/emblemas/?pagina=$i'>$i</a> ";
}

// Exibindo a tabela e os links de paginação
echo $html;
echo "<div class='pagination-box'>$links_paginacao</div>";

?>
