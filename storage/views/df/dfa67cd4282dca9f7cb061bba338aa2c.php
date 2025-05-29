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

/* forms/register_form.twig */
class __TwigTemplate_ab16d8e804e773afe862ee28749e8238 extends Template
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
        yield "<div id=\"register_overlay\" style=\"display:none;border-radius: 3px 3px 0 0;\">
    <div style=\"padding: 60px; text-align: center;\">
        <img src=\"images/finding.gif\" width=\"64\"/>
        <h4 style=\"color:#333;margin-top:30px;\">
            <strong>Registering Account</strong>
        </h4>
        <h5 style=\"color:#444;\">Please wait...</h5>
    </div>
</div>

<form name=\"registerForm\" id=\"registerForm\" action=\"";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("registration"), "html", null, true);
        yield "\" class=\"login nobottommargin\"
      style=\"margin-bottom:20px;\" method=\"post\">


    <h3 class=\"center\" style=\"color:#333;\">Don't have an Account? Register Now.</h3>
    <p class=\"center\" style=\"color:#333;\">Create a quick account and get access to thousands of e-books, audio
        books and magazines. Read anything, anywhere.</p>

    <div class=\"clearfix\"></div>

    <div class=\"row\">
        <div class=\"col-md-6\">
            <div>
                <label for=\"first_name\">First Name <span class=\"text-danger\">*</span></label>
                <input name=\"first_name\" type=\"text\" id=\"first_name\" placeholder=\"Enter first name\"
                       required/>
            </div>
        </div>

        <div class=\"col-md-6\">
            <div>
                <label for=\"last_name\">Last Name <span class=\"text-danger\">*</span></label>
                <input name=\"last_name\" type=\"text\" id=\"last_name\" placeholder=\"Enter last name\" required/>
            </div>
        </div>

        <div class=\"col-md-12\">
            <div>
                <label for=\"email_reg\"> Email Address <span class=\"text-danger\">*</span></label>
                <input name=\"email_reg\" type=\"email\" id=\"email_reg\" placeholder=\"Email Address\"
                       pattern=\"^[A-Za-z0-9](([_\\.\\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\\.\\-]?[a-zA-Z0-9]+)*)\\.([A-Za-z]{2,})\$\"
                       required/>
            </div>
        </div>
    </div>

    <div class=\"clearfix\"></div>

    <div class=\"row\">
        <div class=\"col-md-6\">
            <div>
                <label for=\"country\">Select Country <span class=\"text-danger\">*</span></label>
                <select class=\"chosen-select-no-single\" name=\"country\" id=\"country\" tabindex=\"0\" required>
                    <option value=\"\">Click to select</option>
                    ";
        // line 55
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["countries"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["country"]) {
            // line 56
            yield "                        <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["country"], "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["country"], "html", null, true);
            yield "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['country'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 58
        yield "                </select>
            </div>
        </div>

        <div class=\"col-md-6\">
            <div>
                <label for=\"dob\">Date of Birth <span class=\"text-danger\">*</span></label>
                <input type=\"date\" name=\"dob\" id=\"dob\" />
            </div>
        </div>
    </div>

    <div class=\"clearfix\"></div>


    <div class=\"clearfix\"></div>

    <div class=\"row\" id=\"password_section\">
        <div class=\"col-md-6\">
            <div>
                <label for=\"password\">Password <span class=\"text-danger\">*</span></label>
                <input name=\"password\" type=\"password\" id=\"password\" placeholder=\"Enter password\" minlength=\"6\"/>
            </div>
        </div>

        <div class=\"col-md-6\">
            <div>
                <label for=\"password_repeat\">Confirm Password <span class=\"text-danger\">*</span></label>
                <input name=\"password_repeat\" type=\"password\" id=\"password_repeat\" minlength=\"6\" placeholder=\"Repeat password\"/>
            </div>
        </div>
    </div>

    <div class=\"clearfix\"></div>

    <div class=\"row\">

        <div class=\"col-md-12\">
            <div>
                <label for=\"referral_code\">Did someone referred you? Enter referral code to avail referral
                    benefits.</label>
                <input name=\"referral_code\" type=\"text\" id=\"referral_code\"
                       value=\"";
        // line 100
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["referral_code"] ?? null), "html", null, true);
        yield "\"
                       placeholder=\"Referral Code (Optional)\"/>

            </div>
        </div>
    </div>


        <div class=\"row\">
            <div class=\"col-md-12\">
                <div class=\"checkboxes in-row margin-bottom-20\">
                    <input id=\"check_agree\" type=\"checkbox\" name=\"check_agree\" value=\"1\" required>
                    <label for=\"check_agree\" style=\"color:#333333;\">I agree that the above information is
                        correct.</label>
                </div>
            </div>
        </div>

    <div class=\"clear\"></div>

    <div class=\"col_full nobottommargin text-right\" style=\"margin-top:20px;\">
        <button class=\"button button-3d button-green nomargin\" id=\"register-form-submit\" name=\"register-form-submit\"
                value=\"register\">
            <i class=\"fa fa-arrow-right\"></i>
            Register My Account
        </button>
    </div>

</form>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "forms/register_form.twig";
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
        return array (  160 => 100,  116 => 58,  105 => 56,  101 => 55,  54 => 11,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "forms/register_form.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\forms\\register_form.twig");
    }
}
