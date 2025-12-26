<?php
function createCoachCard($coach) {
    $img = !empty($coach['img_utilisateur']) ? $coach['img_utilisateur'] : 'https://placehold.net/avatar.svg';

    $disciplinesHtml = '';
    if (!empty($coach['disciplines']) && is_array($coach['disciplines'])) {
        foreach ($coach['disciplines'] as $d) {
            $disciplinesHtml .= '<span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">'.$d.'</span>';
        }
    }

    return '
    <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all duration-300 p-6 transform hover:-translate-y-1">
        <img src="'.$img.'" 
             alt="'.$coach['nom'].' '.$coach['prenom'].'" 
             class="w-full h-48 object-cover rounded-lg mb-4">
        <h3 class="text-xl font-bold text-gray-800">'.$coach['nom'].' '.$coach['prenom'].'</h3>
        <p class="text-purple-600 font-semibold text-sm mb-2">'.$coach['annee_experience'].' ans d\'expérience</p>
        <div class="flex flex-wrap gap-2 mb-4">'.$disciplinesHtml.'</div>
        <button class="w-full py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
            <a href="../../pages/Sportif/detailsCoach.php?id_coach='.$coach['id_coach'].'">Voir le profil</a>
        </button>
    </div>';
}
?>