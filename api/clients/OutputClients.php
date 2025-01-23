<?php 

function OutputClients($clients) {
    foreach ($clients as $key => $client) {
        $id = $client['id'];

        echo "
            <tr>
                <td>$id</td>
                <td>Александр</td>
                <td>alex@gmail.com</td>
                <td>89123456789</td>
                <td>12.01.2000</td>
                <td>12.01.2025</td>
                <td onclick=\"MicroModal.show('history-modal')\"><i class='fa fa-history'></i></td>
                <td onclick=\"MicroModal.show('edit-modal')\"><i class='fa fa-pencil'></i></td>
                <td onclick=\"MicroModal.show('delete-modal')\"><i class='fa fa-trash'></i></td>
            </tr>";
    }
}

?>