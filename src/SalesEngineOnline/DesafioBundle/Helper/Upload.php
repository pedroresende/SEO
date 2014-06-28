<?php

namespace SalesEngineOnline\DesafioBundle\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of Upload
 *
 * @author Pedro Resende <pedroresende@mail.resende.biz>
 */
class Upload {

    public function upload($request, $folder, &$fotografia, &$curriculum)
    {
        $position = 0;
        foreach ($request->files as $uploadedFiles) {
            foreach ($uploadedFiles as $uploadedFile) {
                $name = md5(time()).'.'.$uploadedFile->guessClientExtension();
                $uploadedFile->move($folder, $name);
                if ($position == 0) {
                    $fotografia = $name;
                } else {
                    $curriculum = $name;
                }
                $position++;
            }
        }
    }
}
