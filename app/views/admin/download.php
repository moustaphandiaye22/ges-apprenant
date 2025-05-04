<?php
require_once '../vendor/autoload.php'; // Inclure Dompdf pour PDF

// Charger les données des apprenants
$dataFile = __DIR__ . '/../data/data.json';
$data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
$apprenants = $data['apprenants'] ?? [];

if (isset($_GET['format'])) {
    $format = $_GET['format'];

    if ($format === 'pdf') {
        // Générer un fichier PDF
        $html = '<h1>Liste des Apprenants</h1><table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><th>Matricule</th><th>Nom Complet</th><th>Classe</th><th>Référentiel</th><th>Statut</th></tr>';
        foreach ($apprenants as $apprenant) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($apprenant['matricule']) . '</td>';
            $html .= '<td>' . htmlspecialchars($apprenant['nom_complet']) . '</td>';
            $html .= '<td>' . htmlspecialchars($apprenant['classe']) . '</td>';
            $html .= '<td>' . htmlspecialchars($apprenant['referentiel']) . '</td>';
            $html .= '<td>' . htmlspecialchars($apprenant['statut']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        // Utiliser Dompdf pour générer le PDF
        // $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('liste_apprenants.pdf', ['Attachment' => true]);
        exit;
    } elseif ($format === 'excel') {
        // Générer un fichier Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="liste_apprenants.xls"');
        header('Cache-Control: max-age=0');

        // Ajouter les en-têtes
        echo "Matricule\tNom Complet\tClasse\tRéférentiel\tStatut\n";

        // Ajouter les données des apprenants
        foreach ($apprenants as $apprenant) {
            echo implode("\t", [
                $apprenant['matricule'],
                $apprenant['nom_complet'],
                $apprenant['classe'],
                $apprenant['referentiel'],
                $apprenant['statut']
            ]) . "\n";
        }
        exit;
    } else {
        // Format non supporté
        http_response_code(400);
        echo "Format non supporté.";
    }
} else {
    // Aucun format spécifié
    http_response_code(400);
    echo "Aucun format spécifié.";
}