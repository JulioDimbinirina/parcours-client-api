<?php
namespace App\Service;

use App\Entity\Bdc;
use App\Entity\RejectBdc;
use App\Repository\BdcRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SendMailTo extends AbstractController {

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /*
     * Send email and notification
     * $exp (Sender email address)
     * $dest (Destination email address)
     * $obj (Object or header of message)
     * $msg (Content of email or message)
     * $idDevis (Devis id (optional))
     */
    public function sendEmail($exp, $dest, $obj, $msg, $idDevis = null): Response
    {
        if ($idDevis) {
            $message = (new \Swift_Message($obj))
                ->setFrom($exp)
                ->setTo($dest)
                ->setBody($msg, 'text/html');
            $this->mailer->send($message);
            return $this->json('Email envoyé avec succès ...', 200, [], ['groups' => ['update-bdc']]);
        } else {
            $message = (new \Swift_Message($obj))
                ->setFrom($exp)
                ->setTo($dest)
                ->setBody($msg, 'text/html')
                ->attach(\Swift_Attachment::fromPath($this->getParameter('bdc_dir').'devis_'.$idDevis.'.pdf'));
            $this->mailer->send($message);
            return $this->json('Email envoyé avec succès ...', 200, [], ['groups' => ['update-bdc']]);
        }
    }
    public function sendEmail2($exp, $dest, $obj, $msg){
        $message = (new \Swift_Message($obj))
        ->setFrom($exp)
        ->setTo($dest)
        ->setBody($msg, 'text/html');
        return $this->mailer->send($message);
    }
    public function sendEmailNouveauClientExecel($exp, $dest, $obj, $msg, $nomExecel){
        $message = (new \Swift_Message($obj))
        ->setFrom($exp)
        ->setTo($dest)
        ->setBody($msg, 'text/html')
        ->attach(\Swift_Attachment::fromPath($this->getParameter('bdc_dir').$nomExecel.'.xlsx'));
        $this->mailer->send($message);
    }

    public function sendEmailViaTwigTemplate($exp, $dest, $obj, $template, $currentUser, $idDevis, $otherParams = array()): Response
    {
        $devis = $this->getDoctrine()->getRepository(Bdc::class)->find($idDevis);
        $rejectedDevis = $this->getDoctrine()->getRepository(RejectBdc::class)->findOneBy([
            'bdc' => $devis
        ]);

        if ($rejectedDevis) {
            $message = (new \Swift_Message($obj))
                ->setFrom($exp)
                ->setTo($dest)
                ->setBody(
                    $this->renderView(
                        $template,
                        ['bdc' => $devis, 'rejectedBdc' => $rejectedDevis, 'user' => $currentUser, $otherParams]
                    ),
                    "text/html"
                );
        } else {
            $message = (new \Swift_Message($obj))
                ->setFrom($exp)
                ->setTo($dest)
                ->setBody(
                    $this->renderView(
                        $template,
                        ['bdc' => $devis,  'user' => $currentUser]
                    ),
                    "text/html"
                );
        }

        $this->mailer->send($message);

        return $this->json('Email envoyé avec succès ...', 200, [], ['groups' => ['update-bdc']]);
    }
}