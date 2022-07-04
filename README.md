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

-   Please warp all CRUD process in controller using Repository pattern design. Don't forget to write docblock to generate API Documentation if you write an api controller. Example: <br>
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

    example using repository pattern in App\Controller\Api\OrderController.php

    ```php
    <?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Interfaces\OrderInterface;
    use App\Repositories\OrderRepository;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    /**
    * @group Order management
    *
    * APIs for managing orders
    */
    class OrderController extends Controller
    {
        private OrderRepository $orderRepository;

        public function __construct(OrderInterface $orderRepository)
        {
            $this->orderRepository = $orderRepository;
        }

        /**
        * Get all Orders
        *
        * This endpoint allows you to get all orders
        *
        * @return JsonResponse
        */
        public function index(): JsonResponse
        {
            return response()->json([
                'data' => $this->orderRepository->getAllOrders()
            ]);
        }

        /**
        * Get One Order
        *
        * This endpoint allows you to get one Order
        *
        * @urlParam id integer required The ID of the order.
        *
        * @param Request request The request object.
        *
        * @return JsonResponse A JSON response with the data from the order repository.
        */
        public function show(Request $request): JsonResponse
        {
            $orderId = $request->route('id');

            return response()->json([
                'data' => $this->orderRepository->getOrderById($orderId)
            ]);
        }

        /**
        * Create Order
        *
        * This endpoint allows you to create an order
        *
        * @bodyParam client string required The client name. Example: "jhon doe"
        * @bodyParam details string required The item name. Example: "Sample Product"
        *
        * @param Request request The request object.
        *
        * @return JsonResponse A JSON response with the data of the created order.
        */
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

        /**
        * Update Order
        *
        * This endpoint allows you to update an order
        *
        * @urlParam id integer required The ID of the order.
        * @bodyParam client string required The client name. Example: "jhon doe"
        * @bodyParam details string required The item name. Example: "Sample Product"
        *
        * @param Request request The request object
        *
        * @return JsonResponse The response is a JSON object with a data property.
        */
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

        /**
        * Delete Order
        *
        * This endpoint allows you to delete an order
        *
        * @urlParam id integer required The ID of the order.
        *
        * @param Request request The request object.
        *
        * @return JsonResponse A JSON response with a status code of 204.
        */
        public function destroy(Request $request): JsonResponse
        {
            $orderId = $request->route('id');
            $this->orderRepository->deleteOrder($orderId);

            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
    }

    ```

-   Generating the docs:
    ```shell
    php artisan scribe:generate
    ```
    ![Scribe Documentation](https://github.com/nda666/doran-laravel-boilerplate/blob/master/docs.png)

## Dev Depency used:

-   [Scribe](https://scribe.knuckles.wtf/laravel/documenting) - Api Doc Generator
-   [Phpstan](https://github.com/phpstan/phpstan) - PHP Static Analysis Tool
-   [Pest](https://github.com/pestphp/pest) - Elegant PHP Testing Framework
-   [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) - Development tool that ensures the code remains clean and consistent

## Recomended VsCode Plugin

-   [PHP Sniffer & Beautifier](https://marketplace.visualstudio.com/items?itemName=ValeryanM.vscode-phpsab) - Linter plugin for Visual Studio Code provides an interface to phpcs & phpcbf.
