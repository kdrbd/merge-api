        <?php 
            use Core\User;
            use Core\Session;

            require __DIR__.'/../vendor/autoload.php';

            Session::init();
            Session::checkLogin();

            $user = new User();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
                $usrlgn  = $user->userLogin($_POST);
            }
         ?>

         <div class="container m500">
         	<div class="row sp-n f-c-c">
         		<div class="col-xs-12">
         			<div class="tt">
                        <span>Hello <strong> Login your Account </strong> Here...........!! </span>
                   </div> <hr>
                    <h5 class="text-center"><?php if (isset($usrlgn)) {
                        echo $usrlgn;
                    } ?></h5>
         			<form action="" method="post">
         				<table>
         					<tr>
         						<td>Email: </td>
         						<td> <input type="text" name="username"></td>
         					</tr>
         					<tr>
         						<td>Password: </td>
         						<td> <input type="password" name="pass_word"></td>
         					</tr>
         					<tr>
         						<td></td>
         						<td><input type="submit" name="login" value="Login"> <input type="reset" value="Clear"></td>
         					</tr>
         				</table>
         			</form>
         		</div>
         	</div>
         </div>
    </main>