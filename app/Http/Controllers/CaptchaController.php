<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\PngEncoder;

class CaptchaController extends Controller
{
    public function image()
    {
        $code = strtoupper(substr(md5(now()), 0, 5));
        Session::put('captcha_code', $code);

        $manager = new ImageManager(new Driver());
        $width = 150;
        $height = 40;
        $image = $manager->create($width, $height);
        $image->fill('#e0e7ef');

        $noiseColors = ['#b0b0b0', '#a8e6cf', '#ffb6c1']; // gray, light green, pink
        $textColors = [
            '#388e3c',    // green
            '#b0b0b0',    // gray
            '#ff69b4',    // pink
            '#ff0000',    // red
            '#ffff00',    // yellow
            '#0000ff',    // blue
            '#ffa500',    // orange
        ]; 

        // Draw random lines for noise
        for ($i = 0; $i < 5; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $x2 = rand(0, $width);
            $y2 = rand(0, $height);
            $color = $noiseColors[array_rand($noiseColors)];
            $image->drawLine(function($line) use ($x1, $y1, $x2, $y2, $color) {
                $line->from($x1, $y1);
                $line->to($x2, $y2);
                $line->color($color);
                $line->width(rand(1,2));
            });
        }
        // Draw border (rectangle: x1, y1, closure, use size() and border())
        $image->drawRectangle(0, 0, function($rect) use ($width, $height) {
            $rect->size($width, $height);
            $rect->border('#888', 1);
        });

        // Dots for noise (direct arguments, random color)
        for ($i = 0; $i < 50; $i++) {
            $x = rand(0, $width);
            $y = rand(0, $height);
            $color = $noiseColors[array_rand($noiseColors)];
            $image->drawPixel($x, $y, $color);
        }

        // Text with slight rotation per character and random color
        $fontPath = public_path('fonts/arial/ARIAL.TTF');
        $fontSize = 23;
        $startX = 25;
        $y = 18;
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            $angle = rand(-18, 18);
            $color = $textColors[array_rand($textColors)];
            $image->text($char, $startX + $i * 22, $y + rand(-3,3), function ($font) use ($fontPath, $fontSize, $angle, $color) {
                $font->filename($fontPath);
                $font->size($fontSize);
                $font->color($color);
                $font->align('center');
                $font->valign('center');
                $font->angle($angle);
            });
        }

        return response($image->encode(new PngEncoder()))
            ->header('Content-Type', 'image/png');
    }
}
