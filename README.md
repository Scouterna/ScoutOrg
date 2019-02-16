# ScoutOrg
ScoutOrg är ett bibliotek i php som innehåller en datastruktur för en scoutorganisation. Biblioteket är skrivet för att vara grunden till många olika applikationer där organisationen hanteras på scoutkårers hemsidor. Datastrukturen är skriven så att den fungerar bäst för data som hämtas från scoutnet. Biblioteket är dock skrivet så att det även går att implementera andra datakällor som en egen databas eller en blandning av en egen databas och scoutnet.
Just nu finns endast support för joomla men utrymme finns att implementera ScoutOrg i t.ex. wordpress.

## Installation och användning
Just nu finns endast installation för joomla.

Biblioteket använder några paket för php som kanske måste hämtas:

* cURL: ``` sudo apt install php-curl ```
* APCu: ``` sudo apt install php-apcu ```
* Semaphores

Cache-funktionen stöds inte av windows implicit. Antingen kan den inaktiveras genom att sätta livstiden till noll eller så kan man installera eller implementera följande funktioner:

* Semaphores
    * sem_get
    * sem_acquire
    * sem_release
* APCu
    * apcu_fetch
    * apcu_store

### Joomla

#### Installation
Se först till att ha scoutnets webbkoppling aktiverad och ha api-nycklarna.
1. Hitta senaste release från github eller bygg den själv. Den ska heta 'scoutorg.zip'. Kopiera länken till den filen.
2. Logga in som admin i joomla och gå till Extensions->Manage.
3. Välj 'Install From URL' och använd länken till scoutorg.zip.
4. Konfigurera komponenten 'ScoutOrg Component' i System->'Global Configuration' att använda rätt kår och api-nycklar. (Måste göras innan den används av andra extensions)
    * Konfiguera alternativt access till komponentens adminsida.
    * Det går även att konfiguera var datan ska komma från. Standard är från scoutnet, men det går att implementera andra datakällor eller sätt att hämta data.

#### Advancerad konfiguration
Eftersom scoutnet inte ger ut grenarna som varje avdelning är i så ges istället lösningen i scoutorg. Klickar man på Components->ScoutOrg så får man upp listor över grenar och avdelningar där man kan skapa grenar och sätta avdelningarnas grentillhörighet.

Om man inte vill att användarna ska behöva vänta i 20 sekunder ibland när cachen laddar in så kan man sätta upp ett cronjob efter instruktionerna i [/src/cronjob/](src/cronjob)

#### Användning (Utveckling utav extensions)
När biblioteket ska användas av en annan extension behöver man två rader kod:
```
jimport('scoutorg.loader');
$scoutOrg = ScoutOrgLoader::load();
```
Då kommer $scoutOrg att sättas till en instans av typen [Org\Lib\ScoutOrg](src/Org/Lib/ScoutOrg.php). Använder flera olika extensions sig av ```ScoutOrgLoader::load()``` kommer de få ut samma instans. I den senaste [dokumentationen](https://github.com/scouternasetjanster/ScoutOrg/releases/download/v1.2.2/doc_exdev.zip) kan man hitta hur datamodellen ser ut.

## Att bygga projektet och generera dokumentation
Om man vill bygga installationsfiler eller generera dokumentationen för biblioteket krävs att kunna köra 'make' samt några program beroende på vad man bygger.

### Bygga projektet
För att bygga paketet för joomla kan man köra ``` make ``` eller ``` make joomla ```.
Zipfilen kommer då att hamna i 'build/joomla/'.
Om projektet skulle expandera och börja ge stöd för att bygga en plugin för t.ex. wordpress så kommer ``` make ``` att bygga för samtliga mål.

``` make joomla ``` kräver programmet zip: ``` sudo apt install zip ```

### Generera dokumentation
För att generera dokumentationen kör man ``` make doc_exdev ``` för bibliotekets publika interface (för användning vid utveckling utav extensions) och ``` make doc_indev ``` för samtlig dokumentation från alla filer, deras privata, publika, och internmarkerade klasser och klassmedlemmar. Detta kräver att man har php och phpdoc (som /bin/phpdoc).

1. php 7.0 +
2. [phpdoc](https://github.com/phpDocumentor/phpDocumentor2)