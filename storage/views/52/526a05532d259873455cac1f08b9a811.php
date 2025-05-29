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

/* partials/footer.twig */
class __TwigTemplate_854f00fd184d1292a5fdb1f954b60241 extends Template
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
        yield "<div id=\"footer\" class=\"dark\">
    <!-- Main -->
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-md-4 col-sm-6\">
                <h4>About Bazichic</h4>
                <p style=\"color:#ffffff;\">BaziChic is a Chinese Metaphysics consultancy offering Feng Shui audits, BaZi
                    consultations, and Date Selection services. We aim to help you achieve a fulfilling life with
                    happiness, prosperity, and balance. We are here to support you through every stage of your journey,
                    providing guidance and advice as you work toward your goals. Your well-being and success are
                    important to us, and we’re dedicated to assisting you in creating the life you desire. </p>
                <a href=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("about"), "html", null, true);
        yield "\">Learn more</a>
            </div>

            <div class=\"col-md-4 col-sm-6\">
                <h4>Helpful Links</h4>
                <ul class=\"footer-links\" style=\"width: 90%;\">
                    <li><a href=\"";
        // line 18
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
        yield "\">Home</a></li>
                    <li><a href=\"";
        // line 19
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("about"), "html", null, true);
        yield "\">About Us</a></li>
                    <li><a href=\"";
        // line 20
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("e-book-store"), "html", null, true);
        yield "\">Browse</a></li>
                    <li><a href=\"";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("subscription-plans"), "html", null, true);
        yield "\">Membership Plans</a></li>
                    <li><a href=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("contact"), "html", null, true);
        yield "\">Contact Us</a></li>
                    <li><a href=\"";
        // line 23
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("testimonials"), "html", null, true);
        yield "\">Testimonials</a></li>
                    ";
        // line 24
        if (((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 24) && CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "last_name", [], "any", false, false, false, 24)) && CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "email", [], "any", false, false, false, 24))) {
            // line 25
            yield "                        <li><a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my-profile"), "html", null, true);
            yield "\"> My Profile</a></li>
                        <li><a href=\"";
            // line 26
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("dashboard"), "html", null, true);
            yield "\"> My Dashboard</a></li>
                        <li><a href=\"";
            // line 27
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("logout"), "html", null, true);
            yield "\"> Logout</a></li>
                    ";
        } else {
            // line 29
            yield "                        <li><a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("login"), "html", null, true);
            yield "\"> Login</a></li>
                        <li><a href=\"";
            // line 30
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("register"), "html", null, true);
            yield "\"> Create your BaziChic Account</a></li>
                    ";
        }
        // line 32
        yield "

                </ul>
                <div class=\"clearfix\"></div>
            </div>

            <div class=\"col-md-4 col-sm-12\">
                <h4>Contact Us</h4>
                <div class=\"text-widget\">
\t\t\t\t\t<span><b>BaziChic Chinese Metaphysics Consultancy</b>, <br>Kuala Lumpur, <span style=\"color:#fff;\">Malaysia</span> <br>
                    Phone : <span style=\"color:#fff;\">+6011 6326 1781 </span><br>
                    E-Mail : <span> <a href=\"#\">customer_support@bazichic.com</a> </span><br>
                    </span>
                </div>

                <ul class=\"social-icons margin-top-20\">
                    <li><a class=\"facebook\" target=\"_blank\" href=\"https://www.facebook.com/BaziChic\"><i
                                    class=\"icon-facebook\"></i></a></li>
                    ";
        // line 51
        yield "
                </ul>
            </div>

        </div>

        <!-- Copyright -->
        <div class=\"row\">
            <div class=\"col-md-12\">
                <div class=\"copyrights\">© 2019 -2020 | <a href=\"#\">BaziChic Chinese Metaphysics Consultancy</a> | All
                    Rights Reserved.
                </div>
            </div>
        </div>

    </div>

</div>
<!-- Footer / End -->";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/footer.twig";
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
        return array (  134 => 51,  114 => 32,  109 => 30,  104 => 29,  99 => 27,  95 => 26,  90 => 25,  88 => 24,  84 => 23,  80 => 22,  76 => 21,  72 => 20,  68 => 19,  64 => 18,  55 => 12,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/footer.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\footer.twig");
    }
}
