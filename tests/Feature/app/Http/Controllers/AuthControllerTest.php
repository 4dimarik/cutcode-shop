<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     * @return void
     */
    public function it_login_page_success(): void
    {
        $this->get(action([AuthController::class, 'index']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.index');
    }

    /**
     * @test
     * @return void
     */
    public function it_signUp_page_success(): void
    {
        $this->get(action([AuthController::class, 'signUp']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }

    /**
     * @test
     * @return void
     */
    public function it_forgot_page_success(): void
    {
        $this->get(action([AuthController::class, 'forgot']))
            ->assertOk()
            ->assertSee('Восстановить пароль')
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @return void
     */
    public function it_signIn_success(): void
    {
        $password = '1qaz!QAZ';
        $email = 'testing@gdnsite.ru';

        $user = User::factory()->create([
            'email'    => $email,
            'password' => bcrypt($password),
        ]);

        $request = SignInFormRequest::factory()->create([
            'email'    => $email,
            'password' => $password,

        ]);

        $response = $this->post(
            action([AuthController::class, 'signIn']),
            $request
        );

        $response->assertValid();

        $response->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @return void
     */
    public function it_forgotPassword_success(): void
    {
        Notification::fake();

        $email = 'testing@gdnsite.ru';

        $user = User::factory()->create([
            'email' => $email,
        ]);

        $request = ForgotPasswordFormRequest::factory()->create([
            'email' => $email,

        ]);

        $response = $this->post(
            action([AuthController::class, 'forgotPassword']),
            $request
        );

        $response->assertValid();

       Notification::assertSentTo($user, ResetPassword::class);

       $response->assertRedirect(route('home'));
    }

    /**
     * @test
     * @return void
     */
    public function it_logout_success(): void
    {
        $email = 'testing@gdnsite.ru';

        $user = User::factory()->create([
            'email' => $email,
        ]);

        $this->actingAs($user)
            ->delete(action([AuthController::class, 'logOut']));

        $this->assertGuest();
    }

    /**
     * @test
     * @return void
     */
    public function it_store_success(): void
    {
        Notification::fake();
        Event::fake();


        $request = SignUpFormRequest::factory()->create([
            'password'              => '1qaz!QAZ',
            'password_confirmation' => '1qaz!QAZ',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $request['email'],
        ]);

        $response = $this->post(
            action([AuthController::class, 'store']),
            $request
        );

        $response->assertValid();

        $this->assertDatabaseHas('users', [
            'email' => $request['email'],
        ]);

        $user = User::query()
            ->where('email', $request['email'])
            ->first();

        Event::assertDispatched(Registered::class);

        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $event = new Registered($user);
        $listener = new SendEmailNewUserListener();
        $listener->handle($event);

        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);


        $response->assertRedirect(route('home'));
    }
}
