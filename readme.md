<h1>#Laravel project with docker environment(nginx, mysql,php)</h1>

<h4>To run project on your local machine you'll need to do several steps:</h1>

<h4>1 step:</h4>
<p>After cloning the project, go to project folder and type<code> docker run --rm -v $(pwd):/app composer install</code>
if you don't have docker install it</p>

<h4>2 step:</h4>
<p>Set permissions on the project directory<code>sudo chown -R $USER:$USER ~/project_folder</code></p>
<h4>3 step:</h4>
<p>In the project directory:<code>cp .env.example .env</code></p>

<h4>5 step:</h4>
<p><code>nano .env</code></p>


<p>Exit from container</p>

<h4>11 step:</h4>

<p><code>docker-compose exec app php artisan migrate</code></p>

<p>If you need link storage just type <code>docker-compose exec app php artisan storage:link</code></p>
