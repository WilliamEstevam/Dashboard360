"""Verificação HTTP da demonstração.

Uso: python scripts/smoke_test.py [URL]
Padrão: http://127.0.0.1:8080/index.php
"""
import csv
import http.cookiejar
import io
import re
import sys
import urllib.parse
import urllib.request

BASE = sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:8080/index.php'
client = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
def get(query=''):
    response = client.open(BASE + ('?' + query if query else ''), timeout=15)
    assert response.status == 200
    return response.read().decode('utf-8-sig')
def token(html):
    return re.search(r'name="_csrf_token" value="([^"]+)"', html).group(1)
def post(route, data, csrf):
    data['_csrf_token'] = csrf
    response = client.open(BASE + '?route=' + route, urllib.parse.urlencode(data).encode(), timeout=15)
    assert response.status == 200
    return response.read().decode('utf-8-sig')

login = get()
assert 'Explorar demonstração' in login
page = post('login', {'demo_submit':'1'}, token(login))
assert 'Painel de' in page
routes = ['dashboard','tu','cubo_tu','metas','pausas','pausas&cenario=offline','banheiro','tempo_logado',
          'pausas_consolidadas','comunidade','banco_horas','absenteismo','usuarios','ajustes_pausa','links_uteis','perfil']
for route in routes:
    html = get('route=' + route)
    assert 'Dados fictícios' in html, route
    assert not re.search(r'Fatal error|Warning:|Notice:|Parse error', html), route
print('OK: 16 rotas/cenarios com HTTP 200 e identificacao demonstrativa.')
filtered = get('route=tu&supervisor=' + urllib.parse.quote('Supervisão Demo A'))
assert 'Operador Demo 01' in filtered and 'Operador Demo 04' not in filtered
empty = get('route=tu&data_ini=2000-01-01&data_fim=2000-01-02')
assert 'Operador Demo 01' not in empty
assert 'Robô Offline' in get('route=pausas&cenario=offline')
assert 'Operador Demo 01' not in get('route=pausas&cenario=offline')
rows = list(csv.reader(io.StringIO(get('route=tu&export=1')), delimiter=';'))
assert len(rows) == 7 and sum(int(row[3]) for row in rows[1:]) == 600
assert [int(row[4]) for row in rows[1:]] == sorted([int(row[4]) for row in rows[1:]], reverse=True)
assert len(list(csv.reader(io.StringIO(get('route=cubo_tu&export=1')), delimiter=';'))) == 7
print('OK: filtros, periodo vazio, WFM offline e exportacoes CSV consistentes.')
html = get('route=ajustes_pausa')
csrf = token(html)
assert 'Token inválido' in post('ajustes_pausa', {'update_meta_submit':'1','meta_chave':'limite_banheiro','meta_valor':'15'}, 'invalido')
updated = post('ajustes_pausa', {'update_meta_submit':'1','meta_chave':'limite_banheiro','meta_valor':'15'}, csrf)
assert 'Limite atualizado' in updated
assert '15min' in get('route=banheiro').lower()
html = get('route=pausas_consolidadas')
date = re.search(r'name="data_ref" value="([^"]+)"', html).group(1)
csrf = token(html)
justified = post('pausas_consolidadas', {'salvar_justificativa':'1','mat_agente':'DEMO001','atividade_agente':'LANCHE',
                 'data_ref_agente':date,'motivo':'Treinamento demo','observacao':'Registro sintético'}, csrf)
assert 'Justificativa salva' in justified and 'Treinamento demo' in justified
csrf = token(get('route=banco_horas'))
scheduled = post('banco_horas', {'agendar_queima':'1','matricula_agente':'DEMO001','data_queima':'2026-11-15'}, csrf)
assert '2026-11-15' in scheduled
csrf = token(get('route=comunidade'))
posted = post('comunidade', {'post_comunidade_submit':'1','conteudo':'Comunicado de teste sintético','tipo':'informativo'}, csrf)
assert 'Comunicado de teste sintético' in posted
csrf = token(get('route=usuarios'))
created = post('usuarios', {'action':'add_user','nome':'Pessoa Demo Extra','email':'extra@example.com','matricula':'DEMO999','cargo':'operador','password':'nao-persistida'}, csrf)
assert 'Pessoa Demo Extra' in created
csrf = token(get('route=perfil'))
profile = post('perfil', {'update_profile':'1','nome':'<em>Visitante</em>','email':'demo@example.com','senha_atual':'demo360','nova_senha':'','confirma_senha':''}, csrf)
assert '&lt;em&gt;Visitante&lt;/em&gt;' in profile and '<em>Visitante</em>' not in profile
print('OK: CSRF, ajustes de limite, justificativa, agendamento, comunicado, usuario e perfil com escaping.')
