<?php


namespace App\Http\Controllers;


use App\Services\ContentParserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContentParserController extends Controller
{
    private ContentParserService $contentParserService;

    public function __construct(ContentParserService $contentParserService)
    {
        $this->contentParserService = $contentParserService;
    }

    public function index(): View
    {
        $databases = $this->contentParserService->getAvailableDb();

        return view('content-parser', [
            'databases' => $databases ?? []
        ]);
    }

    public function parseContent(): RedirectResponse
    {
        $this->contentParserService->parseFolder();

        return redirect(route('home'));
    }


    /**
     * @throws ValidationException
     */
    public function dropDb(Request $request): bool
    {
        Validator::make($request->all(), [
            'dbs' => 'required|array',
        ])->validate();

        $dbs = $request->dbs ?? [];
        return $this->contentParserService->dropDb($dbs);
    }

    /**
     * @throws ValidationException
     */
    public function downloadContent(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'dbs' => 'required|array',
        ])->validate();

        $databases = $request->dbs ?? [];
        $result = $this->contentParserService->getContent($databases);
        return response()->json($result);
    }


}
