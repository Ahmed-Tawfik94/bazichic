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

/* subscription-plans.twig */
class __TwigTemplate_f0fffec35da294b185d71e8a7eb38b8c extends Template
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

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("layout.twig", "subscription-plans.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 3
        yield "    <div id=\"titlebar\" style=\"background-color:#4E31AA;\">
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-md-12\">
                    <h2 style=\"color:#ffffff;font-weight:600;\">Select Subscription Plan</h2>
                    <nav id=\"breadcrumbs\">
                        <ul>
                            <li><a style=\"color:#ffffff;\" href=\"";
        // line 10
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "\">Home</a></li>
                            <li><a style=\"color:#ffffff;\" href=\"";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/subscription/subscription-plans\">Subscription
                                    Plans</a></li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>


    <!-- Pricing Tables
    ================================================== -->

    <!-- Container / Start -->
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-md-12\">
                <h3 class=\"headline centered margin-top-30\">
                    Subscription Plans
                    <span>10 Days Free Trial for All Plans.</span>
                </h3>
                ";
        // line 34
        yield "                ";
        // line 35
        yield "                ";
        // line 36
        yield "                ";
        // line 37
        yield "                ";
        // line 38
        yield "

            </div>
        </div>

        <section id=\"pricing\" class=\"pricing-content section-padding\">
            <div class=\"container\">
                <div class=\"row text-center\">


                    ";
        // line 48
        yield from $this->loadTemplate("partials/widgets/membership-table.twig", "subscription-plans.twig", 48)->unwrap()->yield($context);
        // line 49
        yield "
                </div>
            </div>
        </section>
        <!-- Row / End -->

    </div>
    <!-- Container / End -->
    <a href=\"";
        // line 57
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/contact\" class=\"flip-banner parallax margin-top-65\" style=\"background-color:#4E31AA;\"
       data-color=\"#4E31AA\" data-color-opacity=\"0.85\" data-img-width=\"2500\" data-img-height=\"1666\">
        <div class=\"flip-banner-content\">
            <h2 class=\"flip-visible\" style=\"font-size:22px;font-weight:600;\">Having any issues or difficulties with your
                account subscription feel free to contact us to resolve your issues.</h2>
            <h2 class=\"flip-hidden\">Contact BaziChic Support <i class=\"sl sl-icon-arrow-right\"></i></h2>
        </div>
    </a>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "subscription-plans.twig";
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
        return array (  128 => 57,  118 => 49,  116 => 48,  104 => 38,  102 => 37,  100 => 36,  98 => 35,  96 => 34,  71 => 11,  67 => 10,  58 => 3,  51 => 2,  40 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "subscription-plans.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\subscription-plans.twig");
    }
}
