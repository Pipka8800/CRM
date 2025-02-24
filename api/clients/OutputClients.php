<?php 
// Правильное оформление стилей внутри PHP
echo '<style>
.date-range-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 12px;
    background: transparent;
    border-radius: 8px;
}

.date-inputs {
    display: flex;
    gap: 12px;
}

.date-input {
    padding: 8px 12px;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
}

.date-input:focus {
    border-color: #4a90e2;
    outline: none;
    box-shadow: 0 0 0 3px rgba(74,144,226,0.1);
}

.date-submit-btn {
    background-color: #4a90e2;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.date-submit-btn:hover {
    background-color: #357abd;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.date-submit-btn:active {
    transform: translateY(0);
    box-shadow: none;
}
</style>';

function OutputClients($clients) {
    foreach ($clients as $key => $client) {
        $id = $client['id'];
        $name = $client['name'];
        $email = $client['email'];
        $phone = $client['phone'];
        $birthday = $client['birthday'];
        $created_at = $client['created_at'];
        echo "
            <tr>
                <td>$id</td>
                <td>$name</td>
                <td>$email</td>
                <td>$phone</td>
                <td>$birthday</td>
                <td>
                    <form class='date-range-form' action='api/clients/ClientHistory.php' method='GET'>
                        <div class='date-inputs'>
                            <input value='$id' name='id' hidden>
                            <input type='date' id='from' name='from' class='date-input'>
                            <input type='date' id='to' name='to' class='date-input'>
                        </div>
                        <button type='submit' class='date-submit-btn'>Сформировать</button>
                    </form>
                </td>
                <td onclick=\"MicroModal.show('history-modal')\"><i class='fa fa-history'></i></td>
                <td onclick=\"MicroModal.show('edit-modal')\"><i class='fa fa-pencil'></i></td>
                <td><a href='api/clients/ClientsDelete.php?id=$id'><i class='fa fa-trash'></i></a></td>
            </tr>";
    }
}

?>