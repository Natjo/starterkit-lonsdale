<?php

function formulaire_contact()
{
    $result = null;
    $infos = array(
        "Une collectivité territoriale",
        "Un centre de tri",
        "Une entreprise",
        "Autre"
    );

    if (isset($_POST['contact_nonce'])) {
        $result = [
            'error' => false,
            'msg' => "<p>Message envoyé avec succés</p>"
        ];

        if (!wp_verify_nonce($_POST['contact_nonce'], 'contact')) {
            $result['error'] = true;
            $result['msg'] = 'Il y a eu un probleme merci de réessayer';
        } else {
            $inputs = array(
                'infos' => !empty($_POST['contact-infos']) ? $_POST['contact-infos'] : '',
                'name' => !empty($_POST['contact-name']) ? $_POST['contact-name'] : '',
                'email' => !empty($_POST['contact-email']) ? $_POST['contact-email'] : '',
                'msg' => !empty($_POST['contact-msg']) ? $_POST['contact-msg'] : '',
                'consent' => !empty($_POST['contact-consent']) ? $_POST['contact-consent'] : ''
            );

            $errors = array();

            // champs requis
            foreach ($inputs as $key => $input) {
                if (empty($input)) {
                    $result['error'] = true;
                    array_push($errors, "Le champ <b>" . $key . "</b> est obligatoire.");
                }
            }

            // email
            if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
                $result['error'] =  true;
                array_push($errors, "L'<b>email</b> n'est pas au bon format.");
            }

            // msg erreurs
            if ($result['error']) {
                $result['msg'] = "<h3>Il y a des erreurs:</h3>";
                $result['msg'] .= "<ul>";
                foreach ($errors as $error) {
                    $result['msg'] .= "<li>" . $error . "</li>";
                }
                $result['msg'] .= "</ul>";
            } else {
                $to = 'j.martin@lonsdale.fr';
                $subject = 'Nouveau contact du site';
                $body = '<h1>Nouvelle demande de contact :</h1>';
                $body .= '<ul>';
                $body .= '<li><b>Vous êtes:</b><br>' . $infos[$_POST['contact-infos']] . '</li>';
                $body .= '<li><b>Nom:</b><br>' . $_POST['contact-name'] . '</li>';
                $body .= '<li><b>Email:</b><br>' . $_POST['contact-email'] . '</li>';
                $body .= '<li><b>Message:</b><br>' . $_POST['contact-msg'] . '</li>';
                $body .= '</ul>';
                $headers = array('Content-Type: text/html; charset=UTF-8');
              
                wp_mail($to, $subject, $body, $headers);
            }
        }
    }

    return $result;
}
