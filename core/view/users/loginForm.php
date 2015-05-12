<?php include "../include/header.php" ?>

      <form action="confirmUser.php" method="post" style="width: 50%;">
        <input type="hidden" name="login" value="true" />

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

        <ul>

          <li>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Your  username" required autofocus maxlength="20" />
          </li>

          <li>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Your  password" required maxlength="20" />
          </li>
            <li>
                <label for="password1">Confirm Password</label>
                <input type="password" name="password1"  placeholder="repeat password" required maxlength="20" />
            </li>
            <li>
                <label for="adress">Adress</label>
                <input type="text" name="adress"  placeholder="adress" required maxlength="20" />
            </li>
            <li>
                <label for="fio">Full name</label>
                <input type="text" name="fio"  placeholder="full name" required maxlength="20" />
            </li>
            <li>
                <label for="phone">phonenumber</label>
                <input type="text" name="phone"  placeholder="phonenumber" required maxlength="20" />
            </li>
        </ul>

        <div class="buttons">
          <input type="submit" name="login" value="Login" />
        </div>

      </form>

<?php include "../include/footer.php" ?>

