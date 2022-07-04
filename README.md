# Doran Stack Boilerplate

A backend boilerplate for PT. Doran Sukses Indonesia

## How to write the code

-   clone this project<br>

```shell
git clone https://github.com/nda666/doran-laravel-boilerplate
```

-   To create a controller please provide a test. Fastest way to create a controller with `--pest` option:

```shell
php artisan make:controller ExampleController --pest
```

-   Please warp all CRUD process in controller using Repository pattern design. Example: <br>
    Create a Repository:

    ```shell
    php artisan make:repository Order
    ```

    App\Interfaces\OrderInterface.php

    ```php
    <?php

    namespace App\Interfaces;

    interface OrderRepositoryInterface
    {
        public function getAllOrders();
        public function getOrderById($orderId);
        public function deleteOrder($orderId);
        public function createOrder(array $orderDetails);
        public function updateOrder($orderId, array $newDetails);
    }
    ```

    App\Interfaces\OrderRepository.php

    ```php
    <?php

    namespace App\Repositories;

    use App\Interfaces\OrderRepositoryInterface;
    use App\Models\Order;

    class OrderRepository implements OrderRepositoryInterface
    {
        public function getAllOrders()
        {
            return Order::all();
        }

        public function getOrderById($orderId)
        {
            return Order::findOrFail($orderId);
        }

        public function deleteOrder($orderId)
        {
            Order::destroy($orderId);
        }

        public function createOrder(array $orderDetails)
        {
            return Order::create($orderDetails);
        }

        public function updateOrder($orderId, array $newDetails)
        {
            return Order::whereId($orderId)->update($newDetails);
        }

        public function getFulfilledOrders()
        {
            return Order::where('is_fulfilled', true);
        }
    }
    ```

    in App\Controller\OrderController.php

    ```php
    <?php

    namespace App\Http\Controllers;

    use App\Interfaces\OrderInterface;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class OrderController extends Controller
    {
        private OrderRepository $orderRepository;

        public function __construct(OrderInterface $orderRepository)
        {
            $this->orderRepository = $orderRepository;
        }

        public function index(): JsonResponse
        {
            return response()->json([
                'data' => $this->orderRepository->getAllOrders()
            ]);
        }

        public function store(Request $request): JsonResponse
        {
            $orderDetails = $request->only([
                'client',
                'details'
            ]);

            return response()->json(
                [
                    'data' => $this->orderRepository->createOrder($orderDetails)
                ],
                Response::HTTP_CREATED
            );
        }

        public function show(Request $request): JsonResponse
        {
            $orderId = $request->route('id');

            return response()->json([
                'data' => $this->orderRepository->getOrderById($orderId)
            ]);
        }

        public function update(Request $request): JsonResponse
        {
            $orderId = $request->route('id');
            $orderDetails = $request->only([
                'client',
                'details'
            ]);

            return response()->json([
                'data' => $this->orderRepository->updateOrder($orderId, $orderDetails)
            ]);
        }

        public function destroy(Request $request): JsonResponse
        {
            $orderId = $request->route('id');
            $this->orderRepository->deleteOrder($orderId);

            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
    }
    ```

## Dev Depency used:

-   [Scribe](https://scribe.knuckles.wtf/laravel/documenting) - Api Doc Generator
-   [Phpstan](https://github.com/phpstan/phpstan) - PHP Static Analysis Tool
-   [Pest](https://github.com/pestphp/pest) - Elegant PHP Testing Framework
-   [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) - Development tool that ensures the code remains clean and consistent

## Recomended VsCode Plugin

-   [PHP Sniffer & Beautifier](https://marketplace.visualstudio.com/items?itemName=ValeryanM.vscode-phpsab) - Linter plugin for Visual Studio Code provides an interface to phpcs & phpcbf.
