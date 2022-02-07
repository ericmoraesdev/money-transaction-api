<?php

namespace Tests\Unit\App\Entities\User;

use TestCase;
use App\Entities\User\User;
use App\Exceptions\User\UserEntityException;
use PHPUnit\Framework\MockObject\MockObject;

class UserTest extends TestCase
{


    /**
     * Testing the populate method.
     *
     * should populate the common user with the given data.
     *
     * @return void
     */
    public function testShouldPopulateTheCommonUserWithGivenData()
    {
        $data = [
            'type' => User::TYPE_COMMON,
            'fullname' => 'Fulano de tal',
            'email' => 'fulanodetal@teste.com',
            'cpf' => '055.667.222-00',
            'password' => '123456'
        ];

        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'setType',
            'setFullname',
            'setEmail',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $user->expects($this->never())->method('setCnpj');

        $user->expects($this->once())->method('setFullname')->with($data['fullname']);
        $user->expects($this->once())->method('setEmail')->with($data['email']);
        $user->expects($this->once())->method('setCpf')->with($data['cpf']);
        $user->expects($this->once())->method('setPassword')->with($data['password']);


        $user->populate($data);
    }

    /**
     * Testing the populate method.
     *
     * should populate the shopkeeper user with the given data.
     *
     * @return void
     */
    public function testShouldPopulateTheShopkeeperUserWithGivenData()
    {
        $data = [
            'type' => User::TYPE_SHOPKEEPER,
            'fullname' => 'Loja do outro fulano',
            'email' => 'contato@lojadooutrofulano.com',
            'cnpj' => '22.897.416/0001-97',
            'password' => '654321'
        ];

        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'setType',
            'setFullname',
            'setEmail',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $user->expects($this->never())->method('setCpf');

        $user->expects($this->once())->method('setFullname')->with($data['fullname']);
        $user->expects($this->once())->method('setEmail')->with($data['email']);
        $user->expects($this->once())->method('setCnpj')->with($data['cnpj']);
        $user->expects($this->once())->method('setPassword')->with($data['password']);


        $user->populate($data);
    }

    /**
     * Testing the populate method.
     *
     * should throw an exception when does not have cpf or cnpj
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenDoesNotHaveCpfOrCnpj()
    {
        $data = [
            'type' => User::TYPE_COMMON,
            'fullname' => 'Fulano de tal',
            'email' => 'email',
            'password' => '123456'
        ];

        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'setFullname',
            'setEmail',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();


        $this->expectException(UserEntityException::class);

        $user->populate($data);
    }

    /**
     * Testing the setFullname method.
     *
     * should throw an exception when passing empty fullname
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenPassingEmptyFullname()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setEmail',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setFullname('');
    }

    /**
     * Testing the getFullname method.
     *
     * should return the fullname
     */
    public function testShouldReturnTheFullname()
    {
        $fullname = 'Fulano de tal';

        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setEmail',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $user->setFullname($fullname);

        $this->assertEquals($fullname, $user->getFullname());
    }

    /**
     * Testing the setEmail method.
     *
     * should throw an exception when passing empty email
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenPassingEmptyEmail()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setEmail('');
    }

    /**
     * Testing the setEmail method.
     *
     * should throw an exception when the email is greater than 150 characters
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenTheEmailIsGreaterThan150Characters()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setEmail(str_repeat('a', 151));
    }

    /**
     * Testing the setEmail method.
     *
     * should throw an exception when the email is not valid
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenTheEmailIsNotValid()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setEmail('email');
    }

    /**
     * Testing the setEmail method.
     *
     * should return the email
     */
    public function testShouldReturnTheEmail()
    {
        $email = 'test@test.com';

        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setCpf',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $user->setEmail($email);

        $this->assertEquals($email, $user->getEmail());
    }

    /**
     * Testing the setCpf method.
     *
     * should throw an exception when passing empty cpf
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenPassingEmptyCpf()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setEmail',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setCpf('');
    }

    /**
     * Testing the setCpf method.
     *
     * should throw an exception when the cpf is greater than 14 characters
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenTheCpfIsGreaterThan14Characters()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setEmail',
            'setCnpj',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setCpf(str_repeat('a', 15));
    }

    /**
     * Testing the setCnpj method.
     *
     * should throw an exception when passing empty cnpj
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenPassingEmptyCnpj()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setEmail',
            'setCpf',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setCnpj('');
    }

    /**
     * Testing the setCnpj method.
     *
     * should throw an exception when the cnpj is greater than 18 characters
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenTheCnpjIsGreaterThan18Characters()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setEmail',
            'setCpf',
            'setPassword'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setCnpj(str_repeat('a', 19));
    }

     /**
     * Testing the setPassword method.
     *
     * should throw an exception when the password is lesser than 6 characters
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenThePasswordIsLesserThan6Characters()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setEmail',
            'setCpf',
            'setCnpj'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setPassword(str_repeat('a', 5));
    }

    /**
     * Testing the setPassword method.
     *
     * should throw an exception when the password is greater than 60 characters
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenThePasswordIsGreaterThan60Characters()
    {
        /** @var MockObject|User */
        $user = $this->getMockBuilder(User::class)->onlyMethods([
            'populate',
            'setType',
            'setFullname',
            'setEmail',
            'setCpf',
            'setCnpj'
        ])->getMock();

        $this->expectException(UserEntityException::class);

        $user->setPassword(str_repeat('a', 61));
    }

}
