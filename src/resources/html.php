<?php

echo '<input type="hidden" id="limit_size_files" value="'.$_SESSION['limit_size_files'].'">';
/**
 * Generates a Bootstrap modal HTML markup with customizable content.
 *
 * This function generates a modal HTML structure using Bootstrap classes and styles.
 * It allows you to create modals with different content, titles, icons, and optional edit IDs.
 *
 * @param string $id - The unique ID for the modal. It will be used to trigger the modal's display.
 * @param string $content - The HTML content to be displayed inside the modal's body.
 * @param string $title - The title of the modal displayed in the header.
 * @param string $icon (optional) - An optional icon to be displayed before the title in the header.
 * @param string $editid (optional) - An optional ID to be used for editing purposes. Will be shown as a hidden span.
 * @param string $size (optional) - Size of Modal (sm,lg,xl).
 * @return void - This function does not return a value. It directly echoes the generated HTML.
 */
function modal($id,$content,$title,$icon = '',$editid = '',$size = ''){
    $editid = $editid != '' ? '<span id="'.$editid.'">0</span>' : '';
    $size = $size != '' ? 'modal-' . $size : '';
    $html = '<div class="modal fade" id="'.$id.'">
        <div class="modal-dialog '.$size.' modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">'.$icon.' '.$title.' '.$editid.'</h4>
                    <button type="button" class="close-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="msgModal"></div>
                    <button class="btn btn-primary loading" style="display:none;">
                        <span class="spinner-border spinner-border-sm"></span>
                        Cargando...
                    </button>
                    '.$content.'
                </div>

            </div>
        </div>
    </div>';
    echo $html;
}

/**
 * Generates a dropdown select HTML markup for selecting a company.
 *
 * This function creates a dropdown select element with options for selecting a company.
 * The select element is populated with company data provided as an array ($company).
 *
 * @param array $company - An array of company data, each containing an 'id' and 'name' field.
 * @param string $id - The ID attribute for the select element.
 * @return string - The generated HTML markup for the company select dropdown.
 */
function companySelect($company,$id) {
    $options = '';
    foreach ($company as $value) {
        $options .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
    } 
    return '<select class="form-select" id="'.$id.'" name="company" required>
                <option value="null" hidden>Selecciona una o ninguna empresa</option>
                <option value="0" selected>Ninguna</option>
                '.$options.'
            </select>';
}

/**
 * Generates HTML code for a set of action buttons based on the specified button configurations.
 *
 * @param array $buttons An associative array containing button configurations.
 *                       Each button configuration should include keys like "modal", "href", "onclick", "class", and "icon".
 *                       - "modal": Specifies the ID of the modal to be shown when the button is clicked.
 *                       - "href": Specifies the URL to be navigated when the button is clicked.
 *                       - "target": Specifies the target attribute for the anchor link when "href" is provided.
 *                       - "onclick": Specifies the JavaScript function to be executed when the button is clicked.
 *                       - "class": Specifies the CSS class for the button.
 *                       - "icon": Specifies the CSS class for the button icon.
 *
 * @return string The generated HTML code for the action buttons.
 */
function generateActionHtml($buttons){
    $html = '<span class="d-flex gap-1 align-items-center">';
    foreach ($buttons as $button) {
        $html .= '<a ';
        if (isset($button["modal"])) {
            $html .= 'data-bs-toggle="modal" data-bs-target="#' . $button["modal"] . '" ';
        } elseif (isset($button["href"])) {
            $html .= 'href="' . $button["href"] . '" ';
            if (isset($button["target"])) {
                $html .= 'target="' . $button["target"] . '" ';
            }
        }
        if (isset($button["onclick"])) {
            $html .= 'onclick="' . $button["onclick"] . '" ';
        }
        $html .= '>';
        $html .= '<button class="' . $button["class"] . '"><i class="fa-solid ' . $button["icon"] . '"></i></button></a>';
    }
    $html .= '</span>';
    return $html;
}

/**
 * Generates HTML code for a set of action buttons based on the provided button names.
 *
 * @param mixed $id           The ID for the record associated with the action buttons.
 * @param string $page        The page name or identifier used to construct modal and JavaScript function names.
 * @param array $arrayButtons An array containing the names of the buttons to be displayed in the specified order.
 *                            The button names should correspond to the keys in the predefined button configurations array.
 *
 * @return string The generated HTML code for the action buttons based on the specified button names.
 */
function btnActions($id, $page, $arrayButtons) {
    $id = addslashes($id);

    $buttons = array(
        "add" => array(
            "modal" => $page . "AddModal",
            "onclick" => $page . "AddModalData(" . $id . ")",
            "class" => "btn btn-primary",
            "icon" => "fa-plus",
        ),
        "view" => array(
            "modal" => $page . "ViewModal",
            "onclick" => $page . "ViewModalData(" . $id . ")",
            "class" => "btn btn-view",
            "icon" => "fa-list",
        ),
        "password" => array(
            "modal" => $page . "PasswordModal",
            "onclick" => $page . "PasswordModalData(" . $id . ")",
            "class" => "btn btn-warning",
            "icon" => "fa-key",
        ),
        "copy" => array(
            "onclick" => "copyToClipboard('" . BASEURL . "view.php?page=ver" . $page . "&" . $page . "=" . $id . "')",
            "class" => "btn btn-dark",
            "icon" => "fa-copy",
        ),
        "update" => array(
            "modal" => $page . "UpdateModal",
            "onclick" => $page . "UpdateModalData(" . $id . ")",
            "class" => "btn btn-warning",
            "icon" => "fa-pen-to-square",
        ),
        "url" => array(
            "href" => BASEURL . "view.php?page=ver" . $page . "&" . $page . "=" . $id,
            "target" => "_blank",
            "class" => "btn btn-dark",
            "icon" => "fa-arrow-up-right-from-square",
        ),
        "delete" => array(
            "modal" => $page . "DeleteModal",
            "onclick" => $page . "DeleteModalData(" . $id . ")",
            "class" => "btn btn-danger",
            "icon" => "fa-trash",
        )
    );

    $filteredButtons = array_intersect_key($buttons, array_flip($arrayButtons));
    $res = generateActionHtml($filteredButtons);

    return $res;
}
