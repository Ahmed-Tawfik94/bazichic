<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* partials/widgets/info_section.twig */
class __TwigTemplate_de7c75485763693eacd0a5c026c24469 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!-- Info Section -->
<div class=\"container\">

    <div class=\"row\">
        <div class=\"col-12 col-md-12\">
            <h2 class=\"headline centered margin-top-80\">
                Read, Review & Share. Earn Loyalty Rewards.
                <span class=\"margin-top-25\">There are so many amazing Chinese metaphysics resources available and we want to compile them all for our readers.</span>
            </h2>
        </div>
    </div>

    <div class=\"row icons-container\">
        <!-- Stage -->

        <!-- Stage -->
        <div class=\"col-md-3\">
            <div class=\"icon-box-2 with-line\">
                <i class=\"im im-icon-Add-UserStar\"></i>
                <h3>Register</h3>
                <p>Register for an account. Get access to all the latest updates from BaziChic.</p>
            </div>
        </div>

        <div class=\"col-md-3\">
            <div class=\"icon-box-2 with-line\">
                <i class=\"im im-icon-Books-2\"></i>
                <h3>Accessibility</h3>
                <p>Choose your favourite e-books, magazines, audiobooks and read or listen them online from your
                    computer, mobilephone or tablet.</p>
            </div>
        </div>

        <!-- Stage -->
        <div class=\"col-md-3\">
            <div class=\"icon-box-2 with-line\">
                <i class=\"im im-icon-Conference\"></i>
                <h3>Review & Share</h3>
                <p>Share your reviews about us on social media. Every referral will be awarded with US\$ 1.</p>
            </div>
        </div>

        <!-- Stage -->
        <div class=\"col-md-3\">
            <div class=\"icon-box-2\">
                <i class=\"im im-icon-Coin\"></i>
                <h3>Loyalty Reward</h3>
                <p>Discount of up to 50% on every renewal.</p>
            </div>
        </div>
    </div>
    <div class=\"col-md-12 centered-content\">
        <a href=\"";
        // line 53
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("register"), "html", null, true);
        yield "\" class=\"button btn btn-purple text-white border margin-top-10 text-decoration-none\"> <i class=\"fa fa-arrow-right\"></i>Get
            Started</a>
    </div>
</div>
<!-- Info Section / End -->

<!-- Flip banner -->
<a href=\"";
        // line 60
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("e-book-store"), "html", null, true);
        yield "\" class=\"flip-banner parallax margin-top-65\"
   style=\"background-color:#4E31AA;\"
   data-color=\"#4E31AA\" data-color-opacity=\"0.85\" data-img-width=\"2500\" data-img-height=\"1666\">
    <div class=\"flip-banner-content\">
        <h2 class=\"flip-visible p-3 p-md-0\">Browse E-Books, Audiobooks & Magazines</h2>
        <h2 class=\"flip-hidden\">GET STARTED <i class=\"sl sl-icon-arrow-right\"></i></h2>
    </div>
</a>
<!-- Flip banner / End -->";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/widgets/info_section.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  106 => 60,  96 => 53,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/widgets/info_section.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\widgets\\info_section.twig");
    }
}
