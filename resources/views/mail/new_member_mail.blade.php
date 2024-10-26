<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
  </head>

  <body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td align="center" style="padding: 20px;" >
        <table class="content" width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 1px solid #cccccc;">
          <!-- Header -->
          <tr>
            <td class="header" style=" text-align: center; color: black; font-size: 34px;">
                <img src="{{ URL::asset('/media/images/TRMC_LOGO.png') }}" width="120" height="120" style="padding-top: 20px;"></img><br>
                Twentse Radio Modelvlieg Club
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="body" style="padding: 40px; text-align: left; font-size: 18px; line-height: 1.6;">
                Beste {{ $name }},<br>
                Je bent zojuist toegevoegd aan de Twentse Radio Modelvlieg Club als
                @switch($club_status)
                  @case(\Modules\Members\Enums\ClubStatus::ASPIRANT_MEMBER->value)
                    Aspirant lid
                    @break
                  @case(\Modules\Members\Enums\ClubStatus::JUNIOR_MEMBER->value)
                    Junior lid
                    @break
                  @case(\Modules\Members\Enums\ClubStatus::MEMBER->value)
                    Lid
                    @break
                  @case(\Modules\Members\Enums\ClubStatus::DONOR->value)
                    Donateur
                    @break
                  @case(\Modules\Members\Enums\ClubStatus::MANAGEMENT->value)
                  Bestuur
                    @break
                @endswitch
                {{ $club_status }}!
                <br><br>
            </td>
          </tr>

            <tr>
              <td class="body" style="text-align: center; font-size: 16px; line-height: 1.6;"">
                Dit is een geautomatiseerd bericht. Neem contact op met de TRMC voor vragen.
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td class="footer" style="padding: 40px; text-align: center; color: rgb(0, 0, 0); font-size: 18px;">
                Copyright &copy; 2024 <a href="https://trmc.nl">TRMC</a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <style>
      @media screen and (max-width: 600px) {
        .content {
            width: 100% !important;
            display: block !important;
            padding: 10px !important;
        }
        .header, .body, .footer {
            padding: 20px !important;
        }
      }
    </style>
  </body>
</html>