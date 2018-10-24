/*!
 * FileInput <_LANG_> Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['kg'] = {
        fileSingle: 'файл',
        filePlural: 'Файлдарды',
        browseLabel: 'Тандоо &hellip;',
        removeLabel: 'Өчүрүү',
        removeTitle: 'Тазалоо',
        cancelLabel: 'Баш тартуу',
        cancelTitle: 'Жүктөөнү токтотуу',
        uploadLabel: 'Жүктөө',
        uploadTitle: 'Тандалган файлдарды жүктөө',
        msgNo: 'Жок',
        msgNoFilesSelected: 'Файл тандала элек',
        msgCancelled: 'Баш тартылды',
        msgPlaceholder: '{files} тандаңыз...',
        msgZoomModalHeading: 'Кененирээк кароо',
        msgFileRequired: 'Жүктөө үчүн файл тандооңуз талап кылынат.',
        msgSizeTooSmall: 'Файл "{name}" (<b>{size} KB</b>) файл өтө кичинекей. Эң аз <b>{minSize} KB</b> болуусу талап кылынат.',
        msgSizeTooLarge: 'Файл "{name}" (<b>{size} KB</b>) файл өтө чоң. Эң көп <b>{maxSize} KB</b> болуусу талап кылынат.',
        msgFilesTooLess: 'Эң аз <b>{n}</b> {files} файл болуусу керек.',
        msgFilesTooMany: 'Тандалган <b>({n})</b> файл лимиттен ашып кетти. Эң көп <b>{m}</b> файл жүктөлө алат.',
        msgFileNotFound: '"{name}" файлы табылган жок!',
        msgFileSecured: 'Security restrictions prevent reading the file "{name}".',
        msgFileNotReadable: '"{name}" файлын окууга мүмкүн эмес.',
        msgFilePreviewAborted: '"{name}" файлы көрсөтүлө албайт.',
        msgFilePreviewError: 'An error occurred while reading the file "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Туура эмес типтеги файл "{name}". "{types}" типтеги файлдар гана жүктөлө алат.',
        msgInvalidFileExtension: 'Invalid extension for file "{name}". "{extensions}" типтеги файлдар гана жүктөлө алат.',
        msgFileTypes: {
            'image': 'image',
            'html': 'HTML',
            'text': 'text',
            'video': 'video',
            'audio': 'audio',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'object'
        },
        msgUploadAborted: 'The file upload was aborted',
        msgUploadThreshold: 'Processing...',
        msgUploadBegin: 'Initializing...',
        msgUploadEnd: 'Done',
        msgUploadEmpty: 'No valid data available for upload.',
        msgUploadError: 'Error',
        msgValidationError: 'Validation Error',
        msgLoading: 'Loading file {index} of {files} &hellip;',
        msgProgress: 'Loading file {index} of {files} - {name} - {percent}% completed.',
        msgSelected: '{n} {files} selected',
        msgFoldersNotAllowed: 'Drag & drop files only! Skipped {n} dropped folder(s).',
        msgImageWidthSmall: 'Width of image file "{name}" must be at least {size} px.',
        msgImageHeightSmall: 'Height of image file "{name}" must be at least {size} px.',
        msgImageWidthLarge: 'Width of image file "{name}" cannot exceed {size} px.',
        msgImageHeightLarge: 'Height of image file "{name}" cannot exceed {size} px.',
        msgImageResizeError: 'Could not get the image dimensions to resize.',
        msgImageResizeException: 'Error while resizing the image.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Файлдарды бул жерге таштаңыз &hellip;',
        dropZoneClickTitle: '<br>(же тандоо үчүн басыңыз {files})',
        fileActionSettings: {
            removeTitle: 'Remove file',
            uploadTitle: 'Upload file',
            uploadRetryTitle: 'Retry upload',
            downloadTitle: 'Download file',
            zoomTitle: 'View details',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'Not uploaded yet',
            indicatorSuccessTitle: 'Uploaded',
            indicatorErrorTitle: 'Upload Error',
            indicatorLoadingTitle: 'Uploading ...'
        },
        previewZoomButtonTitles: {
            prev: 'Мурунку файлды көрүү',
            next: 'Кийинки файлды көрүү',
            toggleheader: 'Toggle header',
            fullscreen: 'Toggle full screen',
            borderless: 'Toggle borderless mode',
            close: 'Жабуу'
        }
    };
})(window.jQuery);