<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRCodeHelper {
    private $outputDir;
    
    public function __construct($outputDir = 'public/qrcodes/') {
        $this->outputDir = rtrim(__DIR__ . '/../../' . $outputDir, '/') . '/';
        
        if (!is_dir($this->outputDir)) {
            if (!mkdir($this->outputDir, 0777, true)) {
                throw new Exception("Failed to create QR code directory: " . $this->outputDir);
            }
        }
        
        if (!is_writable($this->outputDir)) {
            throw new Exception("QR code directory is not writable: " . $this->outputDir);
        }
    }
    
    public function generateMemberQR($memberData) {
        try {
            if (!isset($memberData['member_unique_id']) || 
                !isset($memberData['nom']) || 
                !isset($memberData['prenom']) || 
                !isset($memberData['email'])) {
                throw new Exception("Missing required member data");
            }
            
            $filename = $this->outputDir . $memberData['member_unique_id'] . '.png';
            
            $qrContent = json_encode([
                'MEMBRE_ID' => $memberData['member_unique_id'],
                'NOM ET PRENOM' => $memberData['nom'] . ' ' . $memberData['prenom'],
                'EMAIL' => $memberData['email']
            ]);
            
            if ($qrContent === false) {
                throw new Exception("Failed to encode QR content");
            }
            
            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_L,
                'scale' => 5,
                'imageBase64' => false,
                'quietzoneSize' => 2
            ]);
            
            $qrcode = new QRCode($options);
            $qrcode->render($qrContent, $filename);
            
            if (!file_exists($filename)) {
                throw new Exception("Failed to generate QR code file");
            }
            
            return str_replace(__DIR__ . '/../', '', $filename);
            
        } catch (Exception $e) {
            throw new Exception("QR Code generation failed: " . $e->getMessage());
        }
    }
}