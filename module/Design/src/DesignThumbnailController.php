<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;

class DesignThumbnailController extends BaseController
{
    private $designRepository;
    private $fileService;

    public function __construct(DesignRepository $designRepository, DesignFileService $fileService)
    {
        $this->designRepository = $designRepository;
        $this->fileService = $fileService;
    }

    public function getAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $design = $this->designRepository->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        $size = (int) $this->params()->fromRoute('size', 164);
        if ($size < 10 || $size > 1500) {
            return $this->getBadRequestResponse('Size must be between 10 and 1500');
        }

        $contents = $this->fileService->createThumbnail($design, $size);

        $response = $this->getImageResponse($contents, 'image/png');
        $response->getHeaders()
            ->addHeaderLine('Cache-Control', 'max-age=31556926, public')
            ->addHeaderLine('Pragma', 'public');

        return $response;
    }
}
