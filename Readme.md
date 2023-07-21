<p>nginx http://localhost/</p>
<p>pgadmin http://localhost:8020/</p>
<p>VueDev http://localhost:5173/</p>
<p>Postgres localhost:5432</p>
<p>адрес в сети https://vodokachka.solarbob.ru/</p>

<p>Укажите в файле .env желаемый порт для nginx по умолчанию "80"</p>
<p>Инструкция:</p>
<ol>
    <li>открыть в консоли папку с проектом</li>
    <li>выполнить команду: chmod 777 -R back/</li>
    <li>Запустить скрипт run.sh: bash run.sh</li>
    <li>выполнить команду: docker exec -it php bash</li>
    <li>выполнить команду: php artisan migrate:fresh --seed</li>
</ol>

<p>Перейти по адресу http://localhost:{порт nginx}/ и проверить рабостопосбность проекта</p>
<p>Логин администратора: admin@mail.ru</p>
<p>Пароль: 12345678</p>


