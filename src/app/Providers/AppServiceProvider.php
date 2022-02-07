<?php

namespace App\Providers;
use Illuminate\Http\Request;
use Faker\Factory as FakerFactory;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\ServiceProvider;
use App\Http\Requests\TransactionRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Interfaces\Transaction\TransactionRequestInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TransactionRequestInterface::class, TransactionRequest::class);
        $this->app->bind(RequestInterface::class, Request::class);

        $this->app->singleton(\Faker\Generator::class, function () {
            return FakerFactory::create('pt_BR');
        });

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return sprintf('Database\Factories\%sFactory', class_basename($modelName));
        });
    }
}
